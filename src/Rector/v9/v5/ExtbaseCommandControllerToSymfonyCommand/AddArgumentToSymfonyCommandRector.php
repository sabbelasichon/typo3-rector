<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v5\ExtbaseCommandControllerToSymfonyCommand;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/m/typo3/reference-coreapi/9.5/en-us/ApiOverview/CommandControllers/Index.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v5\ExtbaseCommandControllerToSymfonyCommandRector\ExtbaseCommandControllerToSymfonyCommandRectorTest
 */
final class AddArgumentToSymfonyCommandRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const INPUT_ARGUMENTS = 'input-arguments';

    /**
     * @var array<int, string>
     */
    private const MODE_MAPPING = [
        2 => 'OPTIONAL',
        1 => 'REQUIRED',
    ];

    /**
     * @var string
     */
    private const NAME = 'name';

    /**
     * @var array<string, array<string, mixed>>
     */
    private $commandInputArguments = [];

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add arguments to configure and executed method in Symfony Command', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
protected function configure(): void
{
        $this->setDescription('This is the description of the command');
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
protected function configure(): void
{
        $this->setDescription('This is the description of the command');
        $this->addArgument('foo', \Symfony\Component\Console\Input\InputArgument::REQUIRED, 'The parameter foo', null);
}
CODE_SAMPLE
            ,
                [
                    self::INPUT_ARGUMENTS => [
                        'foo' => [
                            self::NAME => 'foo',
                            'description' => 'The parameter foo',
                            'mode' => 1,
                            'default' => null,
                        ],
                    ],
                ]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if ([] === $this->commandInputArguments) {
            return null;
        }

        if ($this->isName($node->name, 'configure')) {
            return $this->addArgumentsToConfigureMethod($node);
        }

        if ($this->isName($node->name, 'execute')) {
            return $this->addArgumentsToExecuteMethod($node);
        }

        return null;
    }

    /**
     * @param array<string, mixed> $configuration
     */
    public function configure(array $configuration): void
    {
        $commandInputArguments = $configuration[self::INPUT_ARGUMENTS] ?? [];
        $this->commandInputArguments = $commandInputArguments;
    }

    private function addArgumentsToConfigureMethod(ClassMethod $node): ClassMethod
    {
        foreach ($this->commandInputArguments as $commandInputArgument) {
            $mode = $this->createMode((int) $commandInputArgument['mode']);

            $name = new String_($commandInputArgument[self::NAME]);
            $description = new String_($commandInputArgument['description']);
            $defaultValue = $commandInputArgument['default'];
            $node->stmts[] = new Expression($this->nodeFactory->createMethodCall(
                'this',
                'addArgument',
                [$name, $mode, $description, $defaultValue]
            ));
        }

        return $node;
    }

    private function addArgumentsToExecuteMethod(ClassMethod $node): ClassMethod
    {
        if (null === $node->stmts) {
            return $node;
        }

        $argumentStatements = [];

        foreach ($this->commandInputArguments as $commandInputArgument) {
            $name = $commandInputArgument[self::NAME];
            $variable = new Variable($name);
            $inputMethodCall = $this->nodeFactory->createMethodCall('input', 'getArgument', [$name]);
            $assignment = new Assign($variable, $inputMethodCall);

            $argumentStatements[] = new Expression($assignment);
        }

        array_unshift($node->stmts, ...$argumentStatements);

        return $node;
    }

    private function createMode(int $mode): ClassConstFetch
    {
        return $this->nodeFactory->createClassConstFetch(
            'Symfony\Component\Console\Input\InputArgument',
            self::MODE_MAPPING[$mode]
        );
    }
}
