<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v5\ExtbaseCommandControllerToSymfonyCommand;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/m/typo3/reference-coreapi/9.5/en-us/ApiOverview/CommandControllers/Index.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v5\ExtbaseCommandControllerToSymfonyCommandRector\ExtbaseCommandControllerToSymfonyCommandRectorTest
 */
final class AddCommandsToReturnRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const COMMANDS = 'commands';

    /**
     * @var string[]
     */
    private array $commands = [];

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add arguments to configure method in Symfony Command', [
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
        $this->addArgument('foo', \Symfony\Component\Console\Input\InputArgument::REQUIRED, 'The foo argument', null);
}
CODE_SAMPLE
,
                [
                    self::COMMANDS => [
                        'Command' => 'Command',
                    ],
                ]
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Return_::class];
    }

    /**
     * @param Return_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ([] === $this->commands) {
            return null;
        }

        if (! $node->expr instanceof Array_) {
            return null;
        }

        $existingCommands = $this->valueResolver->getValue($node->expr) ?? [];

        $commands = array_filter($this->commands, fn (string $command) =>
            array_reduce(
                $existingCommands,
                fn ($carry, $existingCommand) => $existingCommand['class'] !== $command && $carry,
                true
            ));

        foreach ($commands as $commandName => $command) {
            $node->expr->items[] = new ArrayItem($this->nodeFactory->createArray([
                'class' => $this->nodeFactory->createClassConstReference($command),
            ]), new String_($commandName));
        }

        return $node;
    }

    /**
     * @param array<string, mixed> $configuration
     */
    public function configure(array $configuration): void
    {
        $commandInputArguments = $configuration[self::COMMANDS] ?? [];
        $this->commands = $commandInputArguments;
    }
}
