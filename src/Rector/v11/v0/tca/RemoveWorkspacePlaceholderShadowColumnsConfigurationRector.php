<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v0\tca;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use Ssch\TYPO3Rector\Rector\Tca\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.0/Breaking-92791-NewPlaceholderRecordsRemovedInWorkspaces.html
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.0/Breaking-92497-WorkspacesMovePlaceholdersRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v0\tca\RemoveWorkspacePlaceholderShadowColumnsConfigurationRector\RemoveWorkspacePlaceholderShadowColumnsConfigurationRectorTest
 */
final class RemoveWorkspacePlaceholderShadowColumnsConfigurationRector extends AbstractTcaRector
{
    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('removeWorkspacePlaceholderShadowColumnsConfiguration', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'shadowColumnsForNewPlaceholders' => '',
        'shadowColumnsForMovePlaceholders' => '',
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
    ],
];
CODE_SAMPLE
        )]);
    }

    protected function refactorCtrl(Array_ $ctrlArray): void
    {
        $shadowColumnsForNewPlaceholdersArrayItem = $this->extractArrayItemByKey(
            $ctrlArray,
            'shadowColumnsForNewPlaceholders'
        );
        if ($shadowColumnsForNewPlaceholdersArrayItem instanceof ArrayItem) {
            $this->removeNode($shadowColumnsForNewPlaceholdersArrayItem);
            $this->hasAstBeenChanged = true;
        }

        $shadowColumnsForMovePlaceholdersArrayItem = $this->extractArrayItemByKey(
            $ctrlArray,
            'shadowColumnsForMovePlaceholders'
        );
        if ($shadowColumnsForMovePlaceholdersArrayItem instanceof ArrayItem) {
            $this->removeNode($shadowColumnsForMovePlaceholdersArrayItem);
            $this->hasAstBeenChanged = true;
        }
    }
}
