<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\General\Renaming\RenameStaticMethodRector;
use Ssch\TYPO3Rector\Renaming\ValueObject\RenameStaticMethod;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');

    $rectorConfig
        ->ruleWithConfiguration(RenameStaticMethodRector::class, [
            new RenameStaticMethod(
                'TYPO3\CMS\Core\Utility\GeneralUtility',
                'isAbsPath',
                'TYPO3\CMS\Core\Utility\PathUtility',
                'isAbsolutePath'
            ),
        ]);
};
