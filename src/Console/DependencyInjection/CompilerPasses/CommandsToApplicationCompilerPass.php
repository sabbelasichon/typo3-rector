<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Console\DependencyInjection\CompilerPasses;

use Ssch\TYPO3Rector\Console\Application\Typo3RectorConsoleApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class CommandsToApplicationCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $applicationDefinition = $container->getDefinition(Typo3RectorConsoleApplication::class);

        foreach ($container->getDefinitions() as $name => $definition) {
            if (! is_string($definition->getClass())) {
                continue;
            }

            if (! is_a($definition->getClass(), Command::class, true)) {
                continue;
            }

            $applicationDefinition->addMethodCall('add', [new Reference($name)]);
        }
    }
}
