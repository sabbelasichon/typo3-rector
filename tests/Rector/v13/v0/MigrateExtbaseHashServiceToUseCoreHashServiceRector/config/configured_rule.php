<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO313\v0\MigrateExtbaseHashServiceToUseCoreHashServiceRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config.php');

    $rectorConfig->ruleWithConfiguration(\Rector\Renaming\Rector\Name\RenameClassRector::class, [
        'TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService' => 'TYPO3\\CMS\\Core\\Crypto\\HashService',
    ]);
    $rectorConfig->ruleWithConfiguration(\Rector\Renaming\Rector\MethodCall\RenameMethodRector::class, [
        new \Rector\Renaming\ValueObject\MethodCallRename(
            'TYPO3\\CMS\\Core\\Crypto\\HashService',
            'generateHmac',
            'hmac'
        ),
    ]);
    $rectorConfig->rule(MigrateExtbaseHashServiceToUseCoreHashServiceRector::class);
};
