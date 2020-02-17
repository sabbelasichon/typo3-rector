<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Fluid\ViewHelpers;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Stmt\Class_;
use Rector\PhpParser\Node\Manipulator\ClassManipulator;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper as CmsAbstractViewHelper;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Breaking-82414-RemoveCMSBaseViewHelperClasses.html
 */
final class UseRenderingContextGetControllerContextRector extends AbstractRector
{
    /**
     * @var ClassManipulator
     */
    private $classManipulator;

    public function __construct(ClassManipulator $classManipulator)
    {
        $this->classManipulator = $classManipulator;
    }

    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$this->isObjectType($node, AbstractViewHelper::class) && !$this->isObjectType($node, CmsAbstractViewHelper::class)) {
            return null;
        }

        $this->classManipulator->removeProperty($node, 'controllerContext');

        $this->replaceWithRenderingContextGetControllerContext($node);

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Get controllerContext from renderingContext', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class MyViewHelperAccessingControllerContext extends AbstractViewHelper
{
    protected $controllerContext;

    public function render()
    {
        $controllerContext = $this->controllerContext;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class MyViewHelperAccessingControllerContext extends AbstractViewHelper
{
    public function render()
    {
        $controllerContext = $this->renderingContext->getControllerContext();
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    private function replaceWithRenderingContextGetControllerContext(Class_ $node): void
    {
        foreach ($node->getMethods() as $classMethod) {
            $this->traverseNodesWithCallable((array) $classMethod->getStmts(), function (Node $node) {
                if (!$node instanceof PropertyFetch) {
                    return null;
                }

                if (!$this->isName($node, 'controllerContext')) {
                    return null;
                }

                return $this->createMethodCall($this->createPropertyFetch('this', 'renderingContext'), 'getControllerContext');
            });
        }
    }
}
