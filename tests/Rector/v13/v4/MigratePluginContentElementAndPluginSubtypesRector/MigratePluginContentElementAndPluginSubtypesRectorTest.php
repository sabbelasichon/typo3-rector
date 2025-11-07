<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigratePluginContentElementAndPluginSubtypesRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;
use Ssch\TYPO3Rector\Contract\LocalFilesystemInterface;

final class MigratePluginContentElementAndPluginSubtypesRectorTest extends AbstractRectorTestCase
{
    private FilesystemInterface $filesystem;

    private LocalFilesystemInterface $localFilesystem;

    /**
     * @var string[]
     */
    private array $testFilesToDelete = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->initializeFilesystems();
    }

    protected function tearDown(): void
    {
        foreach ($this->testFilesToDelete as $filename) {
            $this->filesystem->delete($filename);
        }

        parent::tearDown();
    }

    /**
     * @dataProvider provideData()
     */
    public function testWithExistingComposerJson(
        string $extensionKey,
        string $fixtureFile,
        bool $migrationFileShouldExist
    ): void {
        // Arrange
        $composerJson = __DIR__ . '/Fixture/' . $extensionKey . '/composer.json';
        $this->testFilesToDelete[] = $composerJson;
        $this->localFilesystem->write($composerJson, '{
    "name": "typo3/rector",
    "description": "TYPO3 Rector Test Extension",
    "autoload": {
        "psr-4": {
            "TYPO3\\\\Rector\\\\": "Classes/"
        }
    },
    "extra": {
        "typo3/cms": {
            "extension-key": "' . $extensionKey . '"
        }
    }
}
');

        // Act
        $this->doTestFile(__DIR__ . '/Fixture/' . $extensionKey . '/' . $fixtureFile);

        if ($migrationFileShouldExist) {
            $this->testFilesToDelete[] = __DIR__ . '/Fixture/' . $extensionKey . '/Classes/Updates/TYPO3RectorCTypeMigration.php';

            // Assert
            $content = $this->filesystem->read(
                __DIR__ . '/Fixture/' . $extensionKey . '/Classes/Updates/TYPO3RectorCTypeMigration.php'
            );
            self::assertStringEqualsFile(
                __DIR__ . '/Assertions/' . $extensionKey . '/Classes/Updates/TYPO3RectorCTypeMigration.php',
                $content
            );
        } else {
            self::assertFileDoesNotExist(
                __DIR__ . '/Fixture/' . $extensionKey . '/Classes/Updates/TYPO3RectorCTypeMigration.php'
            );
        }
    }

    /**
     * @return \Iterator<array<string|bool>>
     */
    public static function provideData(): \Iterator
    {
        yield 'Test that new migration class is created with correct content with ext_localconf' => [
            'extension1',
            'ext_localconf.php.inc',
            true,
        ];
        yield 'Test that new migration class is created with correct content with TCA overrides' => [
            'extension2',
            'Configuration/TCA/Overrides/tt_content.php.inc',
            true,
        ];
        yield 'Test that content elements will be ignored with ext_localconf' => [
            'extension3',
            'ext_localconf.php.inc',
            false,
        ];
        yield 'Test that content elements will be ignored with TCA overrides' => [
            'extension4',
            'Configuration/TCA/Overrides/tt_content.php.inc',
            false,
        ];
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }

    private function initializeFilesystems(): void
    {
        $this->filesystem = self::getContainer()->get(FilesystemInterface::class);
        $this->localFilesystem = self::getContainer()->get(LocalFilesystemInterface::class);
    }
}
