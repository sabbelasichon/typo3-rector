<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Extbase;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v10\v0\ConfigurationManagerAddControllerConfigurationMethodRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ConfigurationManagerAddControllerConfigurationMethodRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideDataForTest()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    public function provideDataForTest(): Iterator
    {
        yield [
            new SmartFileInfo(__DIR__ . '/Fixture/configuration_manager_add_controller_configuration_method.php.inc'),
        ];
    }

    protected function getRectorClass(): string
    {
        return ConfigurationManagerAddControllerConfigurationMethodRector::class;
    }
}
