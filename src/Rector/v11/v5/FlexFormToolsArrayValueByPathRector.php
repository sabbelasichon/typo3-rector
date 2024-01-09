<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v5;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.5/Deprecation-95254-TwoFlexFormToolsMethods.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v5\FlexFormToolsArrayValueByPathRector\FlexFormToolsArrayValueByPathRectorTest
 */
final class FlexFormToolsArrayValueByPathRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class, MethodCall::class];
    }

    /**
     * @param MethodCall|Expression $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Expression) {
            return $this->refactorSetValueByPath($node);
        }

        if (! $this->isMethodCallOnFlexFormTools($node)) {
            return null;
        }

        if (! $this->isNames($node->name, ['getArrayValueByPath'])) {
            return null;
        }

        $args = [$node->args[1], $node->args[0]];
        return $this->nodeFactory->createStaticCall(
            'TYPO3\\CMS\\Core\\Utility\\ArrayUtility',
            'getValueByPath',
            $args
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated FlexFormTools methods with ArrayUtility methods',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;

$flexFormTools = new FlexFormTools();
$searchArray = [];
$value = $flexFormTools->getArrayValueByPath('search/path', $searchArray);

$flexFormTools->setArrayValueByPath('set/path', $dataArray, $value);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\ArrayUtility;

$searchArray = [];
$value = ArrayUtility::getValueByPath($searchArray, 'search/path');

$dataArray = ArrayUtility::setValueByPath($dataArray, 'set/path', $value);
CODE_SAMPLE
                ),

            ]
        );
    }

    private function refactorSetValueByPath(Expression $node): ?Node
    {
        $methodCall = $node->expr;
        if (! $methodCall instanceof MethodCall) {
            return null;
        }

        if (! $this->isMethodCallOnFlexFormTools($methodCall)) {
            return null;
        }

        $variableName = $this->getName($methodCall->args[1]->value) ?? 'dataArray';

        $variable = new Variable($variableName);
        $staticCall = $this->nodeFactory->createStaticCall(
            'TYPO3\\CMS\\Core\\Utility\\ArrayUtility',
            'setValueByPath',
            [$methodCall->args[1], $methodCall->args[0], $methodCall->args[2]]
        );

        return new Expression(new Assign($variable, $staticCall));
    }

    private function isMethodCallOnFlexFormTools(MethodCall $methodCall): bool
    {
        return $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\\CMS\\Core\\Configuration\\FlexForm\\FlexFormTools')
        );
    }
}
