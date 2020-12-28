<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Breaking-82414-RemoveCMSBaseViewHelperClasses.html
 */
final class UseRenderingContextGetControllerContextRector extends AbstractRector
{
    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isObjectTypes($node, [AbstractViewHelper::class, AbstractViewHelper::class])) {
            return null;
        }
        $this->removeProperties($node, ['controllerContext']);
        $this->replaceWithRenderingContextGetControllerContext($node);
        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Get controllerContext from renderingContext', [new CodeSample(<<<'CODE_SAMPLE'
class MyViewHelperAccessingControllerContext extends AbstractViewHelper
{
    protected $controllerContext;

    public function render()
    {
        $controllerContext = $this->controllerContext;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class MyViewHelperAccessingControllerContext extends AbstractViewHelper
{
    public function render()
    {
        $controllerContext = $this->renderingContext->getControllerContext();
    }
}
CODE_SAMPLE
)]);
    }

    private function replaceWithRenderingContextGetControllerContext(Class_ $node): void
    {
        foreach ($node->getMethods() as $classMethod) {
            $this->traverseNodesWithCallable((array) $classMethod->getStmts(), function (Node $node) {
                if (! $node instanceof PropertyFetch) {
                    return null;
                }
                if (! $this->isName($node, 'controllerContext')) {
                    return null;
                }
                return $this->createMethodCall(
                    $this->createPropertyFetch('this', 'renderingContext'),
                    'getControllerContext'
                );
            });
        }
    }

    /**
     * @param string[] $propertyNames
     */
    private function removeProperties(Class_ $class, array $propertyNames): void
    {
        $this->traverseNodesWithCallable($class, function (Node $node) use ($propertyNames) {
            if (! $node instanceof Property) {
                return null;
            }
            if (! $this->isNames($node, $propertyNames)) {
                return null;
            }
            $this->removeNode($node);
        });
    }
}
