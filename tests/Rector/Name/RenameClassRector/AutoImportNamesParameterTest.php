<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Name\RenameClassRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class AutoImportNamesParameterTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureAutoImportNames');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/autoimport_rename.php';
    }
}
