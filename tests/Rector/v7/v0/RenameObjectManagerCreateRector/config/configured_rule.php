<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig
        ->ruleWithConfiguration(
            RenameMethodRector::class,
            [new MethodCallRename('TYPO3\CMS\Extbase\Object\ObjectManagerInterface', 'create', 'get')]
        );
};
