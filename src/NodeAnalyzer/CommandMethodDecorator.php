<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\NodeAnalyzer;

use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\PhpParser\Node\NodeFactory;
use Rector\NodeNameResolver\NodeNameResolver;
use Symfony\Component\Console\Input\InputArgument;

final class CommandMethodDecorator
{
    /**
     * @var array<int, string>
     */
    private const MODE_MAPPING = [
        InputArgument::OPTIONAL => 'OPTIONAL',
        InputArgument::REQUIRED => 'REQUIRED',
    ];

    /**
     * @readonly
     */
    private NodeFactory $nodeFactory;

    /**
     * @readonly
     */
    private NodeNameResolver $nodeNameResolver;

    public function __construct(NodeFactory $nodeFactory, NodeNameResolver $nodeNameResolver)
    {
        $this->nodeFactory = $nodeFactory;
        $this->nodeNameResolver = $nodeNameResolver;
    }

    /**
     * @param array<array{mode: int, name: string, description: string, default: mixed}> $commandInputArguments
     */
    public function decorate(ClassMethod $classMethod, array $commandInputArguments): void
    {
        if ($commandInputArguments === []) {
            return;
        }

        if ($this->nodeNameResolver->isName($classMethod->name, 'configure')) {
            $this->addArgumentsToConfigureMethod($classMethod, $commandInputArguments);
            return;
        }

        if ($this->nodeNameResolver->isName($classMethod->name, 'execute')) {
            $this->addArgumentsToExecuteMethod($classMethod, $commandInputArguments);
        }
    }

    /**
     * @param array<array{mode: int, name: string, description: string, default: mixed}> $commandInputArguments
     */
    private function addArgumentsToConfigureMethod(ClassMethod $classMethod, array $commandInputArguments): void
    {
        foreach ($commandInputArguments as $commandInputArgument) {
            $mode = $this->createMode($commandInputArgument['mode']);

            $name = new String_($commandInputArgument['name']);
            $description = new String_($commandInputArgument['description']);
            $defaultValue = $commandInputArgument['default'];
            $classMethod->stmts[] = new Expression($this->nodeFactory->createMethodCall(
                'this',
                'addArgument',
                [$name, $mode, $description, $defaultValue]
            ));
        }
    }

    /**
     * @param array<array{mode: int, name: string, description: string, default: mixed}> $commandInputArguments
     */
    private function addArgumentsToExecuteMethod(ClassMethod $classMethod, array $commandInputArguments): void
    {
        if ($classMethod->stmts === null) {
            return;
        }

        $argumentStatements = [];

        foreach ($commandInputArguments as $commandInputArgument) {
            $name = $commandInputArgument['name'];
            $variable = new Variable($name);
            $inputMethodCall = $this->nodeFactory->createMethodCall('input', 'getArgument', [$name]);
            $assignment = new Assign($variable, $inputMethodCall);

            $argumentStatements[] = new Expression($assignment);
        }

        array_unshift($classMethod->stmts, ...$argumentStatements);
    }

    private function createMode(int $mode): ClassConstFetch
    {
        return $this->nodeFactory->createClassConstFetch(
            'Symfony\Component\Console\Input\InputArgument',
            self::MODE_MAPPING[$mode]
        );
    }
}
