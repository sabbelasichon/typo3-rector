<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\NodeAnalyzer;

use PhpParser\Node\Expr\Array_;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Ssch\TYPO3Rector\NodeFactory\CommandArrayItemFactory;

final class CommandArrayDecorator
{
    public function __construct(
        private readonly CommandArrayItemFactory $commandArrayItemFactory,
        private readonly ValueResolver $valueResolver,
    ) {
    }

    /**
     * @param array<string, mixed> $commands
     */
    public function decorateArray(Array_ $array, array $commands): void
    {
        $existingCommands = $this->valueResolver->getValue($array) ?? [];

        $commands = array_filter($commands, fn (string $command) => array_reduce(
            $existingCommands,
            fn ($carry, $existingCommand) => $existingCommand['class'] !== $command && $carry,
            true
        ));

        $arrayItems = $this->commandArrayItemFactory->createArrayItems($commands);
        $array->items = array_merge($array->items, $arrayItems);
    }
}
