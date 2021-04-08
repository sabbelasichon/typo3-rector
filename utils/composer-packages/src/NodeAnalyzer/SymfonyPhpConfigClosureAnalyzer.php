<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\NodeAnalyzer;

use PhpParser\Node\Expr\Closure;
use Rector\NodeNameResolver\NodeNameResolver;

final class SymfonyPhpConfigClosureAnalyzer
{
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;

    public function __construct(NodeNameResolver $nodeNameResolver)
    {
        $this->nodeNameResolver = $nodeNameResolver;
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

        return $this->nodeNameResolver->isName(
            $onlyParam->type,
            'Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator'
        );
    }
}
