<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Backend\Controller\SimpleDataHandlerController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Breaking-82629-TceDbOptionsPrErrAndUPTRemoved.html
 */
final class RemovePropertiesFromSimpleDataHandlerControllerRector extends AbstractRector
{
    /**
<<<<<<< HEAD
     * @return array<class-string<Node>>
=======
     * @return array<class-string<\PhpParser\Node>>
>>>>>>> f7cbd4b... make PHPStan smarted on getNodeTypes()
     */
    public function getNodeTypes(): array
    {
        return [Assign::class];
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->var instanceof Variable) {
            $this->removeVariableNode($node);
            return null;
        }
        if ($node->var instanceof PropertyFetch) {
            $this->removePropertyFetchNode($node);
        }
        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove assignments or accessing of properties prErr and uPT from class SimpleDataHandlerController',
            [
                new CodeSample(<<<'CODE_SAMPLE'
final class MySimpleDataHandlerController extends SimpleDataHandlerController
{
    public function myMethod()
    {
        $pErr = $this->prErr;
        $this->prErr = true;
        $this->uPT = true;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
final class MySimpleDataHandlerController extends SimpleDataHandlerController
{
    public function myMethod()
    {
    }
}
CODE_SAMPLE
),
            ]
        );
    }

    private function removeVariableNode(Assign $assign): void
    {
        $classNode = $assign->expr->getAttribute(AttributeKey::CLASS_NODE);
        if (null === $classNode) {
            return;
        }
        if (! $this->isObjectType($classNode, SimpleDataHandlerController::class)) {
            return;
        }
        if (! $this->isName($assign->expr, 'uPT') && ! $this->isName($assign->expr, 'prErr')) {
            return;
        }
        $this->removeNode($assign);
    }

    private function removePropertyFetchNode(Assign $assign): void
    {
        $classNode = $assign->getAttribute(AttributeKey::CLASS_NODE);
        if (null === $classNode) {
            return;
        }
        if (! $this->isObjectType($classNode, SimpleDataHandlerController::class)) {
            return;
        }
        if (! $this->isName($assign->var, 'uPT') && ! $this->isName($assign->var, 'prErr')) {
            return;
        }
        $this->removeNode($assign);
    }
}
