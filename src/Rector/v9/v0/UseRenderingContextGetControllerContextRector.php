<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.0/Breaking-82414-RemoveCMSBaseViewHelperClasses.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v0\UseRenderingContextGetControllerContextRector\UseRenderingContextGetControllerContextRectorTest
 */
final class UseRenderingContextGetControllerContextRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
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
        $desiredObjectTypes = [
            new ObjectType('TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper'),
            new ObjectType('TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper'),
        ];

        if (! $this->nodeTypeResolver->isObjectTypes($node, $desiredObjectTypes)) {
            return null;
        }

        $this->replaceWithRenderingContextGetControllerContext($node);
        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Get controllerContext from renderingContext', [new CodeSample(
            <<<'CODE_SAMPLE'
class MyViewHelperAccessingControllerContext extends AbstractViewHelper
{
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
        )]);
    }

    private function replaceWithRenderingContextGetControllerContext(Class_ $class): void
    {
        foreach ($class->getMethods() as $classMethod) {
            $this->traverseNodesWithCallable((array) $classMethod->getStmts(), function (Node $node) {
                if (! $node instanceof PropertyFetch) {
                    return null;
                }

                if (! $this->isName($node, 'controllerContext')) {
                    return null;
                }

                return $this->nodeFactory->createMethodCall(
                    $this->nodeFactory->createPropertyFetch('this', 'renderingContext'),
                    'getControllerContext'
                );
            });
        }
    }
}
