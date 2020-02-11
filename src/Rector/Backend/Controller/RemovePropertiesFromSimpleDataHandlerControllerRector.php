<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Backend\Controller;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Backend\Controller\SimpleDataHandlerController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Breaking-82629-TceDbOptionsPrErrAndUPTRemoved.html
 */
final class RemovePropertiesFromSimpleDataHandlerControllerRector extends AbstractRector
{
    /**
     * @inheritDoc
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
        if (!$node->var instanceof Variable && !$node->var instanceof PropertyFetch) {
            return null;
        }

        if ($node->var instanceof Variable) {
            return $this->removeVariableNode($node);
        }

        return $this->removePropertyFetchNode($node);
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Remove assignments or accessing of properties prErr and uPT from class SimpleDataHandlerController', [
            new CodeSample(
                <<<'PHP'
final class MySimpleDataHandlerController extends SimpleDataHandlerController
{
    public function myMethod()
    {
        $pErr = $this->prErr;
        $this->prErr = true;
        $this->uPT = true;
    }
}
PHP
                ,
                <<<'PHP'
final class MySimpleDataHandlerController extends SimpleDataHandlerController
{
    public function myMethod()
    {
    }
}
PHP
            ),
        ]);
    }

    /**
     * @param Assign $node
     *
     * @return null
     */
    private function removeVariableNode(Assign $node)
    {
        $classNode = $node->expr->getAttribute(AttributeKey::CLASS_NODE);

        if (null === $classNode) {
            return null;
        }

        if (!$this->isObjectType($classNode, SimpleDataHandlerController::class)) {
            return null;
        }

        if (!$this->isName($node->expr, 'uPT') && !$this->isName($node->expr, 'prErr')) {
            return null;
        }

        $this->removeNode($node);

        return null;
    }

    /**
     * @param Assign $node
     *
     * @return null
     */
    private function removePropertyFetchNode(Assign $node)
    {
        $classNode = $node->getAttribute(AttributeKey::CLASS_NODE);

        if (null === $classNode) {
            return null;
        }

        if (!$this->isObjectType($classNode, SimpleDataHandlerController::class)) {
            return null;
        }

        if (!$this->isName($node->var, 'uPT') && !$this->isName($node->var, 'prErr')) {
            return null;
        }

        $this->removeNode($node);

        return null;
    }
}
