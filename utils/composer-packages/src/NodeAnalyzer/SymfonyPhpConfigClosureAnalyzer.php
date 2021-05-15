<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\NodeAnalyzer;

use PhpParser\Node\Expr\Closure;
use Rector\NodeNameResolver\NodeNameResolver;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

final class SymfonyPhpConfigClosureAnalyzer
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    public function isPhpConfigClosure(Closure $closure): bool
    {
        if (1 !== count($closure->params)) {
            return false;
        }

        $onlyParam = $closure->params[0];
        if (null === $onlyParam->type) {
            return false;
        }

        return $this->nodeNameResolver->isName($onlyParam->type, ContainerConfigurator::class);
    }
}
