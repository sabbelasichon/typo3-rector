<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Important-105538-ListTypeAndSubTypes.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\DropFifthParameterForExtensionUtilityConfigurePluginRector\DropFifthParameterForExtensionUtilityConfigurePluginRectorTest
 */
final class DropFifthParameterForExtensionUtilityConfigurePluginRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Drop the fifth parameter $pluginType of ExtensionUtility::configurePlugin()', [
            new CodeSample(
                <<<'CODE_SAMPLE'
ExtensionUtility::configurePlugin('ExtensionName', 'PluginName', [], [], 'CType');
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
ExtensionUtility::configurePlugin('ExtensionName', 'PluginName', [], []);
CODE_SAMPLE
            ),
            new CodeSample(
                <<<'CODE_SAMPLE'
ExtensionUtility::configurePlugin('ExtensionName', 'PluginName', [], [], ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
ExtensionUtility::configurePlugin('ExtensionName', 'PluginName', [], []);
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if (count($node->args) < 5) {
            return null;
        }

        unset($node->args[4]);
        return $node;
    }

    private function shouldSkip(StaticCall $staticCall): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticCall,
            new ObjectType('TYPO3\CMS\Extbase\Utility\ExtensionUtility')
        )) {
            return true;
        }

        return ! $this->isName($staticCall->name, 'configurePlugin');
    }
}
