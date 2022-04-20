<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Nimut\TestingFramework\TestCase\UnitTestCase' => 'TYPO3\TestingFramework\Core\Unit\UnitTestCase',
        'Nimut\TestingFramework\TestCase\FunctionalTestCase' => 'TYPO3\TestingFramework\Core\Functional\FunctionalTestCase',
        'Nimut\TestingFramework\TestCase\ViewHelperBaseTestcase' => 'TYPO3\TestingFramework\Fluid\Unit\ViewHelpers\ViewHelperBaseTestcase',
        'Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface' => 'TYPO3\TestingFramework\Core\AccessibleObjectInterface',
        'Nimut\TestingFramework\Exception\Exception' => 'TYPO3\TestingFramework\Core\Exception',
    ]);
};
