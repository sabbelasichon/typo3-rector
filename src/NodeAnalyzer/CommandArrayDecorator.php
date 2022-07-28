<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\NodeAnalyzer;

use PhpParser\Node\Expr\Array_;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Ssch\TYPO3Rector\NodeFactory\CommandArrayItemFactory;

final class CommandArrayDecorator
{
    /**
     * @readonly
     */
    private CommandArrayItemFactory $commandArrayItemFactory;

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(CommandArrayItemFactory $commandArrayItemFactory, ValueResolver $valueResolver)
    {
        $this->commandArrayItemFactory = $commandArrayItemFactory;
        $this->valueResolver = $valueResolver;
    }

    /**
     * @param array<string, mixed> $commands
     */
    public function decorateArray(Array_ $array, array $commands): void
    {
        $existingCommands = $this->valueResolver->getValue($array) ?? [];

        $commands = array_filter($commands, static fn (string $command) => array_reduce(
            $existingCommands,
            static fn ($carry, $existingCommand) => $existingCommand['class'] !== $command && $carry,
            true
        ));

        $arrayItems = $this->commandArrayItemFactory->createArrayItems($commands);
        $array->items = array_merge($array->items, $arrayItems);
    }
}
