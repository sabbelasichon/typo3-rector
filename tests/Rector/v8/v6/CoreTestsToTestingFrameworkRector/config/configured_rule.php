<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Rector\Renaming\Rector\Name\RenameClassRector;
use TYPO3\CMS\Core\Tests\FunctionalTestCase as CoreFunctionalTestCase;
use TYPO3\CMS\Core\Tests\UnitTestCase as CoreUnitTestCase;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig
        ->ruleWithConfiguration(RenameClassRector::class, [
            CoreUnitTestCase::class => UnitTestCase::class,
            CoreFunctionalTestCase::class => FunctionalTestCase::class,
        ]);
};
