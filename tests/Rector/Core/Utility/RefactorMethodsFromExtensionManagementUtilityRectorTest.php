<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Core\Utility;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v9\v0\RefactorMethodsFromExtensionManagementUtilityRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RefactorMethodsFromExtensionManagementUtilityRectorTest extends AbstractRectorTestCase
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
        yield [new SmartFileInfo(__DIR__ . '/Fixture/refactor_extensionmanagement_utility_methods.php.inc')];
    }

    protected function getRectorClass(): string
    {
        return RefactorMethodsFromExtensionManagementUtilityRector::class;
    }
}
