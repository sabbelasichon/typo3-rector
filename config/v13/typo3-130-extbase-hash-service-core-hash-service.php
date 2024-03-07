<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Ssch\TYPO3Rector\TYPO313\v0\MigrateExtbaseHashServiceToUseCoreHashServiceRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('TYPO3\\CMS\\Core\\Crypto\\HashService', 'generateHmac', 'hmac'),
    ]);
    $rectorConfig->ruleWithConfiguration(MigrateExtbaseHashServiceToUseCoreHashServiceRector::class, [
        MigrateExtbaseHashServiceToUseCoreHashServiceRector::ADDITIONAL_SECRET => 'changeMe',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService' => 'TYPO3\\CMS\\Core\\Crypto\\HashService',
        'TYPO3\\CMS\\Extbase\\Security\\Exception\\InvalidArgumentForHashGenerationException' => 'TYPO3\CMS\Core\Exception\Crypto\InvalidHashStringException',
        'TYPO3\\CMS\\Extbase\\Security\\Exception\\InvalidHashException' => 'TYPO3\CMS\Core\Exception\Crypto\InvalidHashStringException',
    ]);
};
