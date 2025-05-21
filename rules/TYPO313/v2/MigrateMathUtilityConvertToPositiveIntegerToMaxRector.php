<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v2;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\Int_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.2/Deprecation-103785-DeprecateMathUtilityConvertToPositiveInteger.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v2\MigrateMathUtilityConvertToPositiveIntegerToMaxRector\MigrateMathUtilityConvertToPositiveIntegerToMaxRectorTest
 */
final class MigrateMathUtilityConvertToPositiveIntegerToMaxRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `MathUtility::convertToPositiveInteger()` to `max()`', [new CodeSample(
            <<<'CODE_SAMPLE'
MathUtility::convertToPositiveInteger($pageId)
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
max(0, $pageId)
CODE_SAMPLE
        )]);
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

        if (count($node->args) !== 1) {
            return null;
        }

        return new FuncCall(new Name('max'), [$this->nodeFactory->createArg(new Int_(0)), $node->args[0]]);
    }

    private function shouldSkip(StaticCall $node): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Utility\MathUtility')
        )) {
            return true;
        }

        return ! $this->isName($node->name, 'convertToPositiveInteger');
    }
}
