<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v4\RegisterIconToIconFileRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Filesystem\FileInfoFactory;

final class RegisterIconToIconFileRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
        // This is not accurate. Unfortunately the content of the files are not mutable, so we added multiple times virtually
        $this->assertSame(3, $this->removedAndAddedFilesCollector->getAddedFileCount());

        $addedFilesWithContent = $this->removedAndAddedFilesCollector->getAddedFilesWithContent();

        $commandsFixture = $this->getService(FileInfoFactory::class)->createFileInfoFromPath(
            __DIR__ . '/Fixture/Expected/Configuration/Icons.php.inc'
        );

        // Assert that commands file is added
        $addedCommandsFile = $addedFilesWithContent[2];
        $this->assertStringContainsString('Icons.php', $addedCommandsFile->getFilePath());
        $this->assertSame($commandsFixture->getContents(), $addedCommandsFile->getFileContent());
    }

    /**
     * @return Iterator<array<string>>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture/my_extension/');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
