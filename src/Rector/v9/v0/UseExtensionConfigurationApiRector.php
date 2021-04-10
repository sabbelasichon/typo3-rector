<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Deprecation-82254-DeprecateGLOBALSTYPO3_CONF_VARSEXTextConf.html
 */
final class UseExtensionConfigurationApiRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FuncCall::class, ArrayDimFetch::class];
    }

    /**
     * @param FuncCall|ArrayDimFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof FuncCall && ! $this->isName($node->name, 'unserialize')) {
            return null;
        }

        // We assume ArrayDimFetch as default value here.
        if ($node instanceof FuncCall) {
            $firstArgument = $node->args[0] ?? null;

            if (null === $firstArgument) {
                return null;
            }

            if (! $firstArgument->value instanceof ArrayDimFetch) {
                return null;
            }

            $extensionConfiguration = $firstArgument->value;
        } else {
            $extensionConfiguration = $node;
        }

        if ($this->shouldSkip($extensionConfiguration)) {
            return null;
        }

        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);

        // Assignments are not handled. Makes no sense at the moment
        if ($parentNode instanceof Assign && $parentNode->var === $extensionConfiguration) {
            return null;
        }

        if ($parentNode instanceof Isset_) {
            return new ArrayDimFetch(new ArrayDimFetch(new ArrayDimFetch(
                new Variable('GLOBALS'), new String_('TYPO3_CONF_VARS')
            ), new String_('EXTENSIONS')), $extensionConfiguration->dim);
        }

        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall(GeneralUtility::class, 'makeInstance', [
                $this->nodeFactory->createClassConstReference(ExtensionConfiguration::class),
            ]),
            'get',
            [$extensionConfiguration->dim]
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Use the new ExtensionConfiguration API instead of $GLOBALS[\'TYPO3_CONF_VARS\'][\'EXT\'][\'extConf\'][\'foo\']',
            [
                new CodeSample(<<<'CODE_SAMPLE'
$extensionConfiguration2 = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['foo'], ['allowed_classes' => false]);
CODE_SAMPLE
                    , <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$extensionConfiguration2 = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('foo');
CODE_SAMPLE
                ),

            ]);
    }

    private function shouldSkip(ArrayDimFetch $node): bool
    {
        $extConf = $node->var;
        if (! $extConf instanceof ArrayDimFetch) {
            return true;
        }

        if (null === $extConf->dim) {
            return true;
        }

        if (! $this->valueResolver->isValue($extConf->dim, 'extConf')) {
            return true;
        }

        if (! property_exists($node->var, 'var')) {
            return true;
        }

        $ext = $node->var->var;
        if (! $ext instanceof ArrayDimFetch) {
            return true;
        }

        if (null === $ext->dim) {
            return true;
        }

        if (! $this->valueResolver->isValue($ext->dim, 'EXT')) {
            return true;
        }

        $typo3ConfVars = $node->var->var->var;
        if (! $typo3ConfVars instanceof ArrayDimFetch) {
            return true;
        }

        if (null === $typo3ConfVars->dim) {
            return true;
        }

        if (! $this->valueResolver->isValue($typo3ConfVars->dim, 'TYPO3_CONF_VARS')) {
            return true;
        }

        $globals = $node->var->var->var->var;
        if (! $this->isName($globals, Typo3NodeResolver::GLOBALS)) {
            return true;
        }

        if (null === $node->dim) {
            return true;
        }

        return ! $this->isName($node->dim, '_EXTKEY') && null === $this->valueResolver->getValue($node->dim);
    }
}
