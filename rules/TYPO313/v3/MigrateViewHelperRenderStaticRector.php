<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v3;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\PropertyItem;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\VariadicPlaceholder;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.3/Deprecation-104789-RenderStaticForFluidViewHelpers.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateViewHelperRenderStaticRector\MigrateViewHelperRenderStaticRectorTest
 */
final class MigrateViewHelperRenderStaticRector extends AbstractRector
{
    /**
     * This method helps other to understand the rule
     * and to generate documentation.
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Migrate static ViewHelpers to object-based ViewHelpers',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext): string
    {
        return $renderChildrenClosure();
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class MyViewHelper extends AbstractViewHelper
{
    public function render(): string
    {
        return $this->renderChildren();
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $classNode
     */
    public function refactor(Node $classNode): ?Node
    {
        // Only refactor ViewHelper classes that implement RenderStatic traits with
        // explicit definition of the contentArgumentName
        if (! $this->classCanBeMigrated($classNode)) {
            return null;
        }

        $staticMethodNode = $classNode->getMethod('renderStatic');
        if ($staticMethodNode === null) {
            return null;
        }

        // Replacements for renderStatic() method arguments
        $argumentsParamName = $this->nodeNameResolver->getName($staticMethodNode->params[0]->var);
        $renderClosureParamName = $this->nodeNameResolver->getName($staticMethodNode->params[1]->var);
        $renderingContextParamName = $this->nodeNameResolver->getName($staticMethodNode->params[2]->var);

        // Replace local variables in render function with object properties
        $this->traverseNodesWithCallable(
            $staticMethodNode->stmts ?? [],
            function (Node $node) use (
                $argumentsParamName,
                $renderClosureParamName,
                $renderingContextParamName
            ): ?Node {
                $renderingContextReplacement = new PropertyFetch(new Variable('this'), new Identifier(
                    'renderingContext'
                ));
                $childrenClosureCallReplacement = new MethodCall(new Variable('this'), new Identifier(
                    'renderChildren'
                ));
                $childrenClosureReplacement = new MethodCall(new Variable('this'), new Identifier('renderChildren'), [
                    new VariadicPlaceholder(),
                ]);
                $argumentsReplacement = new PropertyFetch(new Variable('this'), new Identifier('arguments'));
                // If the renderChildren closure is called directly, the whole function call needs to be replaced
                if (
                    $node instanceof FuncCall
                    && $node->name instanceof Variable
                    && $this->nodeNameResolver->getName($node->name) === $renderClosureParamName
                ) {
                    return $childrenClosureCallReplacement;
                }

                // Replace usages of variables
                if ($node instanceof Variable) {
                    switch ($this->nodeNameResolver->getName($node)) {
                        case $argumentsParamName:
                            return $argumentsReplacement;

                        case $renderingContextParamName:
                            return $renderingContextReplacement;

                        case $renderClosureParamName:
                            return $childrenClosureReplacement;
                    }
                }

                return null;
            }
        );

        // Rename method and make it non-static
        $staticMethodNode->params = [];
        $staticMethodNode->name = new Identifier('render');
        $staticMethodNode->flags ^= Modifiers::STATIC;

        // Use new API to set content argument
        $resolveContentArgumentNameMethod = $classNode->getMethod('resolveContentArgumentName');
        $contentArgumentNameProperty = $classNode->getProperty('contentArgumentName');
        if ($resolveContentArgumentNameMethod instanceof ClassMethod) {
            // Rename content argument method
            $resolveContentArgumentNameMethod->name = new Identifier('getContentArgumentName');
        } elseif ($contentArgumentNameProperty instanceof Property) {
            // Remove property and extract its default value
            $defaultValueExpression = null;
            foreach ($classNode->stmts as $stmtKey => $stmt) {
                if (! $stmt instanceof Property) {
                    continue;
                }

                foreach ($stmt->props as $propKey => $prop) {
                    if ($prop instanceof PropertyItem && $prop->name->toString() === 'contentArgumentName') {
                        $defaultValueExpression = $prop->default;
                        unset($stmt->props[$propKey]);
                    }
                }

                if ($stmt->props === []) {
                    unset($classNode->stmts[$stmtKey]);
                }
            }

            $getContentArgumentNameMethod = new ClassMethod('getContentArgumentName');
            $getContentArgumentNameMethod->flags = Modifiers::PUBLIC;
            $getContentArgumentNameMethod->stmts[] = new Return_($defaultValueExpression);
            $getContentArgumentNameMethod->returnType = new Identifier('string');

            $classNode->stmts[] = $getContentArgumentNameMethod;
        }

        // Remove traits
        foreach ($classNode->stmts as $stmtKey => $stmt) {
            if (! $stmt instanceof TraitUse) {
                continue;
            }

            foreach ($stmt->traits as $traitKey => $trait) {
                if ($this->isNames(
                    $trait,
                    [CompileWithRenderStatic::class, CompileWithContentArgumentAndRenderStatic::class]
                )) {
                    unset($stmt->traits[$traitKey]);
                }
            }

            if ($stmt->traits === []) {
                unset($classNode->stmts[$stmtKey]);
            }
        }

        return $classNode;
    }

    private function classCanBeMigrated(Class_ $classNode): bool
    {
        // Skip ViewHelpers without renderStatic() method (this shouldn't happen)
        $staticMethodNode = $classNode->getMethod('renderStatic');
        if (! $staticMethodNode instanceof ClassMethod || ! $staticMethodNode->isStatic()) {
            return false;
        }

        foreach ($classNode->getTraitUses() as $traitUse) {
            foreach ($traitUse->traits as $trait) {
                // Skip ViewHelpers where content argument is determined automatically
                if ($this->isName($trait, CompileWithContentArgumentAndRenderStatic::class)) {
                    $contentArgumentNameProperty = $classNode->getProperty('contentArgumentName');
                    if ($contentArgumentNameProperty && $contentArgumentNameProperty->props !== []) {
                        return true;
                    }

                    $resolveContentArgumentNameMethod = $classNode->getMethod('resolveContentArgumentName');
                    if ($resolveContentArgumentNameMethod instanceof ClassMethod) {
                        return true;
                    }
                }

                if ($this->isName($trait, CompileWithRenderStatic::class)) {
                    return true;
                }
            }
        }

        return false;
    }
}
