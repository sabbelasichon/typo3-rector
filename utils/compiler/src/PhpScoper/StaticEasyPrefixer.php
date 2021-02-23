<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Compiler\PhpScoper;

final class StaticEasyPrefixer
{
    /**
     * @var string[]
     */
    public const EXCLUDED_CLASSES = [
        'Symfony\Component\Console\Style\SymfonyStyle',
        // part of public interface of configs.php
        'Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator',
        // this is not prefixed on few places by php-scoper by default, probably some bug
        'Doctrine\Inflector\Inflector',
    ];

    /**
     * @var string[]
     */
    private const EXCLUDED_NAMESPACES = [
        // naturally
        'Rector\*',
        // naturally
        'Ssch\*',
        // we use this API a lot
        'PhpParser\*',
        'TYPO3Fluid\*',
        'TYPO3\*',
        'ApacheSolrForTypo3\*',
        'Nimut\*',

        // phpstan needs to be here, as phpstan-extracted/vendor autoload is statically generated and namespaces cannot be changed
        'PHPStan\*',

        // this is public API of a Rector rule
        'Symplify\RuleDocGenerator\*',

        // for configuring sets with ValueObjectInliner
        'Symplify\SymfonyPhpConfig\*',

        // doctrine annotations to autocomplete
        'Doctrine\ORM\Mapping\*',
    ];

    /**
     * @return string[]
     */
    public static function getExcludedNamespacesAndClasses(): array
    {
        return array_merge(self::EXCLUDED_NAMESPACES, self::EXCLUDED_CLASSES);
    }
}
