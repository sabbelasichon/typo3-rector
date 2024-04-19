<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    /**
     * @see https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/8.1/Feature-75454-DoctrineDBALForDatabaseConnections.html
     * The rules for TYPO3 prior v10 have been deleted; that is why these are added here.
     */
    $rectorConfig->ruleWithConfiguration(
        RenameClassConstFetchRector::class,
        [
            new RenameClassAndConstFetch(\PDO::class, 'PARAM_INT', 'TYPO3\CMS\Core\Database\Connection', 'PARAM_INT'),
            new RenameClassAndConstFetch(\PDO::class, 'PARAM_STR', 'TYPO3\CMS\Core\Database\Connection', 'PARAM_STR'),
            new RenameClassAndConstFetch(\PDO::class, 'PARAM_NULL', 'TYPO3\CMS\Core\Database\Connection', 'PARAM_NULL'),
            new RenameClassAndConstFetch(\PDO::class, 'PARAM_LOB', 'TYPO3\CMS\Core\Database\Connection', 'PARAM_LOB'),
            new RenameClassAndConstFetch(\PDO::class, 'PARAM_BOOL', 'TYPO3\CMS\Core\Database\Connection', 'PARAM_BOOL'),
        ]
    );
};
