<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\FuncCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Deprecation-82254-DeprecateGLOBALSTYPO3_CONF_VARSEXTextConf.html
 */
final class UseExtensionConfigurationApiRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node->name, 'unserialize')) {
            return null;
        }

        $firstArgument = array_shift($node->args);

        if (null === $firstArgument) {
            return null;
        }

        if (! $firstArgument->value instanceof ArrayDimFetch) {
            return null;
        }

        $extensionConfiguration = $firstArgument->value;

        if ($this->shouldSkip($extensionConfiguration)) {
            return null;
        }

        return $this->createMethodCall($this->createStaticCall(GeneralUtility::class, 'makeInstance', [
            $this->createClassConstantReference(ExtensionConfiguration::class),
        ]), 'get', [$extensionConfiguration->dim]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Use the new ExtensionConfiguration API instead of $GLOBALS[\'TYPO3_CONF_VARS\'][\'EXT\'][\'extConf\'][\'foo\']',
            [
                new CodeSample(<<<'PHP'
$extensionConfiguration2 = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['foo'], ['allowed_classes' => false]);
PHP
                    , <<<'PHP'
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$extensionConfiguration2 = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('foo');
PHP
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

        if (! $this->isValue($extConf->dim, 'extConf')) {
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

        if (! $this->isValue($ext->dim, 'EXT')) {
            return true;
        }

        $typo3ConfVars = $node->var->var->var;
        if (! $typo3ConfVars instanceof ArrayDimFetch) {
            return true;
        }

        if (null === $typo3ConfVars->dim) {
            return true;
        }

        if (! $this->isValue($typo3ConfVars->dim, 'TYPO3_CONF_VARS')) {
            return true;
        }

        $globals = $node->var->var->var->var;
        if (! $this->isName($globals, Typo3NodeResolver::GLOBALS)) {
            return true;
        }

        if (null === $node->dim) {
            return true;
        }

        return ! $this->isName($node->dim, '_EXTKEY') && null === $this->getValue($node->dim);
    }
}
