<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig
        ->ruleWithConfiguration(RenameClassRector::class, [
            'TYPO3\CMS\Core\Tests\UnitTestCase' => 'TYPO3\TestingFramework\Core\Unit\UnitTestCase',
            'TYPO3\CMS\Core\Tests\FunctionalTestCase' => 'TYPO3\TestingFramework\Core\Functional\FunctionalTestCase',
        ]);
};
