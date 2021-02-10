<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Name\RenameClassRector;

use Iterator;
use Rector\Core\Configuration\Option;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\PostRector\NameImportingPostRector;
use Ssch\TYPO3Rector\Tests\Rector\Name\RenameClassRector\Source\FirstOriginalClass;
use Ssch\TYPO3Rector\Tests\Rector\Name\RenameClassRector\Source\SecondOriginalClass;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @see NameImportingPostRector
 */
final class SkipAutoImportNamesParameterTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/SkipAutoImportNames');
    }

    protected function provideConfigFileInfo(): ?SmartFileInfo
    {
        return new SmartFileInfo(__DIR__ . '/config/autoimport_with_skip.php');
    }
}
