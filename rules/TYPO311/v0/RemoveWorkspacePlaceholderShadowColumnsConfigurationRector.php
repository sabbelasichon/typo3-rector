<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v0;

use PhpParser\Node\Expr\Array_;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.0/Breaking-92791-NewPlaceholderRecordsRemovedInWorkspaces.html
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.0/Breaking-92497-WorkspacesMovePlaceholdersRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v0\RemoveWorkspacePlaceholderShadowColumnsConfigurationRector\RemoveWorkspacePlaceholderShadowColumnsConfigurationRectorTest
 */
final class RemoveWorkspacePlaceholderShadowColumnsConfigurationRector extends AbstractTcaRector
{
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
        $this->hasAstBeenChanged = $this->removeArrayItemFromArrayByKey($ctrlArray, 'shadowColumnsForNewPlaceholders');
        $this->hasAstBeenChanged = $this->removeArrayItemFromArrayByKey($ctrlArray, 'shadowColumnsForMovePlaceholders');
    }
}
