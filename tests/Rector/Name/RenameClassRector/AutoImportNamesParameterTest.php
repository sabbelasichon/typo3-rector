<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Name\RenameClassRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractCommunityRectorTestCase;
use Ssch\TYPO3Rector\PostRector\NameImportingPostRector;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @see NameImportingPostRector
 */
final class AutoImportNamesParameterTest extends AbstractCommunityRectorTestCase
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
        return new SmartFileInfo(__DIR__ . '/config/autoimport_rename.php');
    }
}
