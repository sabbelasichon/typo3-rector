<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\FileProcessor\Resources\Files\Rector\v12\v0\RenameExtTypoScriptFiles;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RenameExtTypoScriptFilesFileRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
        $this->assertSame(1, $this->removedAndAddedFilesCollector->getRemovedFilesCount());
        $this->assertCount(1, $this->removedAndAddedFilesCollector->getAddedFilesWithContent());
    }

    /**
     * @return Iterator<array<string>>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture/my_extension/', '*.txt.inc');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
