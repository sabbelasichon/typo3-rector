<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigratePluginContentElementAndPluginSubtypesRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;

final class MigratePluginContentElementAndPluginSubtypesRectorTest extends AbstractRectorTestCase
{
    private FilesystemInterface $filesystem;

    /**
     * @var string[]
     */
    private array $testFilesToDelete = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->initializeFilesystem();
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
    public function testWithExistingComposerJson(string $extensionKey): void
    {
        // Arrange
        $composerJson = __DIR__ . '/Fixture/' . $extensionKey . '/composer.json';
        $this->testFilesToDelete[] = $composerJson;
        $this->filesystem->write($composerJson, '{
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
        $this->doTestFile(__DIR__ . '/Fixture/' . $extensionKey . '/ext_localconf.php.inc');
        $this->doTestFile(__DIR__ . '/Fixture/' . $extensionKey . '/Configuration/TCA/Overrides/tt_content.php.inc');
        $this->testFilesToDelete[] = __DIR__ . '/Fixture/' . $extensionKey . '/Classes/Updates/TYPO3RectorCTypeMigration.php';

        // Assert
        $content = $this->filesystem->read(
            __DIR__ . '/Fixture/' . $extensionKey . '/Classes/Updates/TYPO3RectorCTypeMigration.php'
        );
        self::assertStringEqualsFile(
            __DIR__ . '/Assertions/' . $extensionKey . '/Classes/Updates/TYPO3RectorCTypeMigration.php',
            $content
        );
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function provideData(): \Iterator
    {
        yield 'Test that new migration class is created with correct content' => ['extension1'];
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }

    private function initializeFilesystem(): void
    {
        $this->filesystem = self::getContainer()
            ->get(FilesystemInterface::class);
    }
}
