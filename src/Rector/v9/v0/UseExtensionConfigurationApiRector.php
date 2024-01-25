<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.0/Deprecation-82254-DeprecateGLOBALSTYPO3_CONF_VARSEXTextConf.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v0\UseExtensionConfigurationApiRector\UseExtensionConfigurationApiRectorTest
 */
final class UseExtensionConfigurationApiRector extends AbstractScopeAwareRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FuncCall::class, Isset_::class, ArrayDimFetch::class];
    }

    /**
     * @param FuncCall|Isset_|ArrayDimFetch $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if ($node instanceof FuncCall) {
            return $this->refactorUnserialize($node);
        }

        if ($node instanceof Isset_) {
            return $this->refactorIsset($node);
        }

        if ($this->shouldSkip($node)) {
            return null;
        }

        if ($scope->isInFirstLevelStatement()) {
            return null;
        }

        if ($node->dim === null) {
            return null;
        }

        return $this->createMethodCall($node->dim);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Use the new ExtensionConfiguration API instead of $GLOBALS[\'TYPO3_CONF_VARS\'][\'EXT\'][\'extConf\'][\'foo\']',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$extensionConfiguration2 = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['foo'], ['allowed_classes' => false]);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$extensionConfiguration2 = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('foo');
CODE_SAMPLE
                ),

            ]
        );
    }

    private function shouldSkip(ArrayDimFetch $node): bool
    {
        $extConf = $node->var;
        if (! $extConf instanceof ArrayDimFetch) {
            return true;
        }

        if (! $extConf->dim instanceof Expr) {
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

        if (! $ext->dim instanceof Expr) {
            return true;
        }

        if (! $this->valueResolver->isValue($ext->dim, 'EXT')) {
            return true;
        }

        $typo3ConfVars = $node->var->var->var;
        if (! $typo3ConfVars instanceof ArrayDimFetch) {
            return true;
        }

        if (! $typo3ConfVars->dim instanceof Expr) {
            return true;
        }

        if (! $this->valueResolver->isValue($typo3ConfVars->dim, 'TYPO3_CONF_VARS')) {
            return true;
        }

        $globals = $node->var->var->var->var;
        if (! $this->isName($globals, Typo3NodeResolver::GLOBALS)) {
            return true;
        }

        return ! $node->dim instanceof Expr;
    }

    private function refactorUnserialize(FuncCall $node): ?Node
    {
        if (! $this->isName($node->name, 'unserialize')) {
            return null;
        }

        // We assume ArrayDimFetch as default value here.
        $firstArgument = $node->args[0] ?? null;

        if (! $firstArgument instanceof Arg) {
            return null;
        }

        $arrayDimFetch = null;
        if ($firstArgument->value instanceof Coalesce) {
            $arrayDimFetch = $firstArgument->value->left;
        } elseif ($firstArgument->value instanceof ArrayDimFetch) {
            $arrayDimFetch = $firstArgument->value;
        }

        if (! $arrayDimFetch instanceof ArrayDimFetch) {
            return null;
        }

        if ($this->shouldSkip($arrayDimFetch)) {
            return null;
        }

        if ($arrayDimFetch->dim === null) {
            return null;
        }

        if ($firstArgument->value instanceof Coalesce) {
            $firstArgument->value->left = $this->createMethodCall($arrayDimFetch->dim);

            return $firstArgument->value;
        }

        return $this->createMethodCall($arrayDimFetch->dim);
    }

    private function refactorIsset(Isset_ $node): ?Node
    {
        $vars = [];
        $hasChanged = false;
        foreach ($node->vars as $var) {
            if (! $var instanceof ArrayDimFetch) {
                $vars[] = $var;
                continue;
            }

            if ($this->shouldSkip($var)) {
                $vars[] = $var;
                continue;
            }

            $hasChanged = true;
            $vars[] = new ArrayDimFetch(new ArrayDimFetch(new ArrayDimFetch(
                new Variable('GLOBALS'),
                new String_('TYPO3_CONF_VARS')
            ), new String_('EXTENSIONS')), $var->dim);
        }

        if (! $hasChanged) {
            return null;
        }

        $node->vars = $vars;

        return $node;
    }

    private function createMethodCall(Expr $dim): MethodCall
    {
        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall('TYPO3\CMS\Core\Utility\GeneralUtility', 'makeInstance', [
                $this->nodeFactory->createClassConstReference('TYPO3\CMS\Core\Configuration\ExtensionConfiguration'),
            ]),
            'get',
            [$dim]
        );
    }
}
