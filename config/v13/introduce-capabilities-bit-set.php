<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use TYPO3\CMS\Core\Resource\Capabilities;
use TYPO3\CMS\Core\Resource\ResourceStorageInterface;

return static function (RectorConfig $rectorConfig): void {
    // constants only have been moved into new Capabilities class
    $capabilities = [
        'CAPABILITY_BROWSABLE',
        'CAPABILITY_PUBLIC',
        'CAPABILITY_WRITABLE',
        'CAPABILITY_HIERARCHICAL_IDENTIFIERS',
    ];

    $configuration = array_map(static fn ($capability) => new RenameClassAndConstFetch(
        ResourceStorageInterface::class,
        $capability,
        Capabilities::class,
        $capability
    ), $capabilities);

    $rectorConfig->ruleWithConfiguration(RenameClassConstFetchRector::class, $configuration);
};
