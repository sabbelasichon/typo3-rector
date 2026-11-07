<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v2;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.2/Important-108796-InternalShortcutClassesRenamedToBookmark.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v2\RenameShortcutClassesToBookmarkRector\RenameShortcutClassesToBookmarkRectorTest
 */
final class RenameShortcutClassesToBookmarkRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Rename internal shortcut classes to bookmark', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Backend\ToolbarItems\ShortcutToolbarItem;
use TYPO3\CMS\Backend\Controller\ShortcutController;
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Backend\ToolbarItems\BookmarkToolbarItem;
use TYPO3\CMS\Backend\Controller\BookmarkController;
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
