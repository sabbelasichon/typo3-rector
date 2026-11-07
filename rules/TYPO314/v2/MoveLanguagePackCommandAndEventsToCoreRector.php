<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v2;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.2/Deprecation-109027-MoveLanguageUpdateCommandAndEventsToEXTcore.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v2\MoveLanguagePackCommandAndEventsToCoreRector\MoveLanguagePackCommandAndEventsToCoreRectorTest
 */
final class MoveLanguagePackCommandAndEventsToCoreRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Move language:update command and language pack events to EXT:core', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Install\Command\LanguagePackCommand;
use TYPO3\CMS\Install\Service\Event\ModifyLanguagePackRemoteBaseUrlEvent;
use TYPO3\CMS\Install\Service\Event\ModifyLanguagePacksEvent;
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Command\UpdateLanguagePackCommand;
use TYPO3\CMS\Core\Localization\Event\ModifyLanguagePackRemoteBaseUrlEvent;
use TYPO3\CMS\Core\Localization\Event\ModifyLanguagePacksEvent;
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
