<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/11.3/Deprecation-94137-SwitchBehaviorOfArrayUtilityarrayDiffAssocRecursive.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v3\SwitchBehaviorOfArrayUtilityMethodsRector\SwitchBehaviorOfArrayUtilityMethodsRectorTest
 */
final class SwitchBehaviorOfArrayUtilityMethodsRector extends AbstractRector
{
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

        $useAssocBehavior = isset($node->args[2]) && $this->valueResolver->getValue($node->args[2]->value);

        if ($useAssocBehavior) {
            return null;
        }

        return $this->nodeFactory->createStaticCall(
            'TYPO3\CMS\Core\Utility\ArrayUtility',
            'arrayDiffKeyRecursive',
            [$node->args[0], $node->args[1]]
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Handles the methods arrayDiffAssocRecursive() and arrayDiffKeyRecursive() of ArrayUtility',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$foo = ArrayUtility::arrayDiffAssocRecursive([], [], true);
$bar = ArrayUtility::arrayDiffAssocRecursive([], [], false);
$test = ArrayUtility::arrayDiffAssocRecursive([], []);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$foo = ArrayUtility::arrayDiffAssocRecursive([], [], true);
$bar = ArrayUtility::arrayDiffKeyRecursive([], []);
$test = ArrayUtility::arrayDiffKeyRecursive([], []);
CODE_SAMPLE
                ),

            ]
        );
    }

    private function shouldSkip(StaticCall $node): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Utility\ArrayUtility')
        )) {
            return true;
        }

        return ! $this->isName($node->name, 'arrayDiffAssocRecursive');
    }
}
