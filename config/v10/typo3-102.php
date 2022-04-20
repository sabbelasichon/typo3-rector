<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v10\v2\ExcludeServiceKeysToArrayRector;
use Ssch\TYPO3Rector\Rector\v10\v2\InjectEnvironmentServiceIfNeededInResponseRector;
use Ssch\TYPO3Rector\Rector\v10\v2\MoveApplicationContextToEnvironmentApiRector;
use Ssch\TYPO3Rector\Rector\v10\v2\UseActionControllerRector;
use Ssch\TYPO3Rector\Rector\v10\v2\UseTypo3InformationForCopyRightNoticeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->rule(MoveApplicationContextToEnvironmentApiRector::class);

    $rectorConfig->rule(ExcludeServiceKeysToArrayRector::class);

    $rectorConfig->rule(UseActionControllerRector::class);

    $rectorConfig->rule(UseTypo3InformationForCopyRightNoticeRector::class);

    $rectorConfig->rule(InjectEnvironmentServiceIfNeededInResponseRector::class);
};
