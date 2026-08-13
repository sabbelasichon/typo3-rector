<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-108148-StrictTypesInFluidViewHelpers.html
 * @see https://github.com/TYPO3/Fluid/pull/1194
 * @see https://github.com/TYPO3/Fluid/pull/1062
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveDefaultValueForRequiredArgumentsInViewHelpersRector\RemoveDefaultValueForRequiredArgumentsInViewHelpersRectorTest
 */
final class RemoveDefaultValueForRequiredArgumentsInViewHelpersRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove default value for required arguments in ViewHelpers', [new CodeSample(
            <<<'CODE_SAMPLE'
$this->registerArgument('requiredArgument', 'int', 'An example of a required argument', true, 'default');
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$this->registerArgument('requiredArgument', 'int', 'An example of a required argument', true);
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node->name, 'registerArgument')) {
            return null;
        }

        if (! $this->isObjectType($node->var, new ObjectType('TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper'))) {
            return null;
        }

        if (count($node->args) < 5) {
            return null;
        }

        if (! $this->valueResolver->isTrue($node->args[3]->value)) {
            return null;
        }

        unset($node->args[4]);
        $node->args = array_values($node->args);

        return $node;
    }
}
