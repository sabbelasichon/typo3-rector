<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\MethodCallToStaticCall;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');

    $rectorConfig
        ->ruleWithConfiguration(MethodCallToStaticCallRector::class, [
            new MethodCallToStaticCall(ObjectManagerInterface::class, 'get', GeneralUtility::class, 'makeInstance'),
            new MethodCallToStaticCall(ObjectManager::class, 'get', GeneralUtility::class, 'makeInstance'),
        ]);
};
