<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v3;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.3/Important-109517-SetupExtensionMergedIntoBackendExtension.html
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.3/Deprecation-109517-AddJavaScriptModulesEvent.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v3\MoveSetupExtensionClassesToBackendRector\MoveSetupExtensionClassesToBackendRectorTest
 */
final class MoveSetupExtensionClassesToBackendRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Move classes of EXT:setup to EXT:backend', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Setup\Event\AddJavaScriptModulesEvent;
use TYPO3\CMS\Setup\Form\Element\AvatarElement;
use TYPO3\CMS\Setup\UserFunctions\UserSettingsItemsProcFunc;
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Event\AddUserSettingsJavaScriptModulesEvent;
use TYPO3\CMS\Backend\Form\Element\AvatarElement;
use TYPO3\CMS\Backend\UserFunctions\UserSettingsItemsProcFunc;
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [];
    }

    public function refactor(Node $node): ?Node
    {
        return null;
    }
}
