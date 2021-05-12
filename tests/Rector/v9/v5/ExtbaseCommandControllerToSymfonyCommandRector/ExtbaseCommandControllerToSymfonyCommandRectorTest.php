<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v5\ExtbaseCommandControllerToSymfonyCommandRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ExtbaseCommandControllerToSymfonyCommandRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
        $this->assertSame(3, $this->removedAndAddedFilesCollector->getAddedFileCount());

        $addedFilesWithContent = $this->removedAndAddedFilesCollector->getAddedFilesWithContent();

        $commandsFixture = new SmartFileInfo(__DIR__ . '/Fixture/Expected/Configuration/Commands.php.inc');
        $commandFixture = new SmartFileInfo(__DIR__ . '/Fixture/Expected/Classes/Command/ImportTeamCommand.php.inc');

        // Assert that command is added
        #$importTeamCommand = $addedFilesWithContent[0];
        #$this->assertSame($commandFixture->getContents(), $importTeamCommand->getFileContent());

        // Assert that commands file is added
        $addedCommandsFile = $addedFilesWithContent[2];
        $this->assertStringContainsString('Commands.php', $addedCommandsFile->getFilePath());
        $this->assertSame($commandsFixture->getContents(), $addedCommandsFile->getFileContent());
    }

    /**
     * @return Iterator<SmartFileInfo>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture/my_extension/Classes/Controller/Command');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
