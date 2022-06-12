<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\typo3;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-96222-AddGetOptionsToWidgetInterface.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\AddMethodToWidgetInterfaceClassesRector\AddMethodToWidgetInterfaceClassesRectorTest
 */
final class AddMethodToWidgetInterfaceClassesRector extends AbstractRector
{
    /**
     * @var string
     */
    private const GET_OPTIONS = 'getOptions';

    public function __construct(
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $getOptionsMethod = $this->createGetOptionsMethod();

        $node->stmts = array_merge((array) $node->stmts, [$getOptionsMethod]);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add getOptions() to classes that implement the WidgetInterface', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;

class MyClass implements WidgetInterface
{
    private readonly array $options;

    public function renderWidgetContent(): string
    {
        return 'foo';
    }
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;

class MyClass implements WidgetInterface
{
    private readonly array $options;

    public function renderWidgetContent(): string
    {
        return 'foo';
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
CODE_SAMPLE
        )]);
    }

    private function shouldSkip(Class_ $class): bool
    {
        if (! $this->nodeTypeResolver->isObjectType(
            $class,
            new ObjectType('TYPO3\CMS\Dashboard\Widgets\WidgetInterface')
        )) {
            return true;
        }

        $className = $this->getName($class);
        if (null === $className) {
            return true;
        }

        if (! $this->reflectionProvider->hasClass($className)) {
            return true;
        }

        // TODO: as far as i understand this should be active, but does currently prevent the code migration
        // $classReflection = $this->reflectionProvider->getClass($className);
        // if ($classReflection->hasMethod(self::GET_OPTIONS)) {
        //     return true;
        // }

        return null !== $class->getMethod(self::GET_OPTIONS);
    }

    private function createGetOptionsMethod(): ClassMethod
    {
        $configurationMethod = $this->nodeFactory->createPublicMethod(self::GET_OPTIONS);
        $configurationMethod->returnType = new Identifier('array');

        $assign = $this->nodeFactory->createPropertyFetch(new Variable('this'), 'options');
        $configurationMethod->stmts = [new Return_($assign)];

        return $configurationMethod;
    }
}
