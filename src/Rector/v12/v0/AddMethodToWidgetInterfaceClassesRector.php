<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use Rector\Rector\AbstractRector;
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
        $implementsInterface = false;
        foreach ($class->implements as $interface) {
            if ($this->isName($interface, 'TYPO3\CMS\Dashboard\Widgets\WidgetInterface')) {
                $implementsInterface = true;
            }
        }

        if (! $implementsInterface) {
            return true;
        }

        return $class->getMethod(self::GET_OPTIONS) instanceof ClassMethod;
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
