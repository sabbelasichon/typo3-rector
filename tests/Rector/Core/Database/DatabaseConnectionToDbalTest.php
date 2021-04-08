<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Core\Database;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class DatabaseConnectionToDbalTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/../../../../config/database-connection-to-dbal.php';
    }
}
