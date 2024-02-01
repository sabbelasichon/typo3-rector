<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\RenameStaticMethod;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config.php');

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
