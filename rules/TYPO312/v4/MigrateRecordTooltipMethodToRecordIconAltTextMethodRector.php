<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Exception\ShouldNotHappenException;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.4/Deprecation-100459-BackendUtilitygetRecordToolTip.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v4\MigrateRecordTooltipMethodToRecordIconAltTextMethodRector\MigrateRecordTooltipMethodToRecordIconAltTextMethodRectorTest
 */
final class MigrateRecordTooltipMethodToRecordIconAltTextMethodRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Migrate `BackendUtility::getRecordToolTip()` to `BackendUtility::getRecordIconAltText()`',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Utility\BackendUtility;

$link = '<a href="#" ' . BackendUtility::getRecordToolTip('tooltip') . '>my link</a>';
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Utility\BackendUtility;

$link = '<a href="#" title="' . BackendUtility::getRecordIconAltText('tooltip') . '">my link</a>';
CODE_SAMPLE
                ),
            ]
        );
    }

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

        $concatenationWithTitleAttribute = [
            new String_('title="'),
            $this->nodeFactory->createStaticCall(
                'TYPO3\CMS\Backend\Utility\BackendUtility',
                'getRecordIconAltText',
                $node->args
            ),
            new String_('"'),
        ];

        try {
            return $this->nodeFactory->createConcat($concatenationWithTitleAttribute);
        } catch (ShouldNotHappenException $shouldNotHappenException) {
            return null;
        }
    }

    private function shouldSkip(StaticCall $node): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Backend\Utility\BackendUtility')
        )) {
            return true;
        }

        return ! $this->isName($node->name, 'getRecordToolTip');
    }
}
