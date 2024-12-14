<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Rector\AbstractArrayDimFetchTcaRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.4/Deprecation-105076-PluginContentElementAndPluginSubTypes.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigratePluginContentElementAndPluginSubtypesTCARector\MigratePluginContentElementAndPluginSubtypesTCARectorTest
 */
final class MigratePluginContentElementAndPluginSubtypesTCARector extends AbstractArrayDimFetchTcaRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate plugin content element and plugin subtypes (list_type) TCA', [new CodeSample(
            <<<'CODE_SAMPLE'
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $pluginSignature,
    'after:subheader',
);
CODE_SAMPLE
        )]);
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        $pluginSignature = $node->var;
        if (! $pluginSignature instanceof ArrayDimFetch) {
            return null;
        }

        if (! $pluginSignature->dim instanceof String_ && ! $pluginSignature->dim instanceof Variable) {
            return null;
        }

        $rootLine = ['TCA', 'tt_content', 'types', 'list', 'subtypes_addlist'];
        $result = $this->isInRootLine($pluginSignature, $rootLine);
        if (! $result) {
            return null;
        }

        return $this->nodeFactory->createStaticCall(
            'TYPO3\\CMS\\Core\\Utility\\ExtensionManagementUtility',
            'addToAllTCAtypes',
            $this->nodeFactory->createArgs([
                new String_('tt_content'),
                new String_('--div--;Configuration,pi_flexform,'),
                $pluginSignature->dim,
                new String_('after:subheader'),
            ])
        );
    }
}
