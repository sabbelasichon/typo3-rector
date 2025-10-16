<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateTableGarbageCollectionTaskConfigurationViaGlobalsRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;

final class MigrateTableGarbageCollectionTaskConfigurationViaGlobalsRectorTest extends AbstractRectorTestCase
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
     * @dataProvider provideData
     */
    public function testWithNonExistingComposerJson(
        string $extensionKey,
        ?string $existingSchedulerTCAContent = null
    ): void {
        // Arrange
        if ($existingSchedulerTCAContent !== null) {
            $tx_scheduler_task = __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/TCA/Overrides/tx_scheduler_task.php';
            $this->testFilesToDelete[] = $tx_scheduler_task;
            $this->filesystem->write($tx_scheduler_task, $existingSchedulerTCAContent);
        }

        // Act
        $this->doTestFile(__DIR__ . '/Fixture/' . $extensionKey . '/ext_localconf.php.inc');
        $this->testFilesToDelete[] = __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/TCA/Overrides/tx_scheduler_task.php';

        // Assert
        $content = $this->filesystem->read(
            __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/TCA/Overrides/tx_scheduler_task.php'
        );
        self::assertStringEqualsFile(
            __DIR__ . '/Assertions/' . $extensionKey . '/Configuration/TCA/Overrides/tx_scheduler_task.php',
            $content
        );
    }

    /**
     * @dataProvider provideData
     */
    public function testWithExistingComposerJson(
        string $extensionKey,
        ?string $existingSchedulerTCAContent = null
    ): void {
        // Arrange
        $composerJson = __DIR__ . '/Fixture/' . $extensionKey . '/composer.json';
        $this->testFilesToDelete[] = $composerJson;
        $this->filesystem->write($composerJson, '{
    "extra": {
        "typo3/cms": {
            "extension-key": "' . $extensionKey . '"
        }
    }
}
');

        if ($existingSchedulerTCAContent !== null) {
            $tx_scheduler_task = __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/TCA/Overrides/tx_scheduler_task.php';
            $this->testFilesToDelete[] = $tx_scheduler_task;
            $this->filesystem->write($tx_scheduler_task, $existingSchedulerTCAContent);
        }

        // Act
        $this->doTestFile(__DIR__ . '/Fixture/' . $extensionKey . '/ext_localconf.php.inc');
        $this->testFilesToDelete[] = __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/TCA/Overrides/tx_scheduler_task.php';

        // Assert
        $content = $this->filesystem->read(
            __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/TCA/Overrides/tx_scheduler_task.php'
        );
        self::assertStringEqualsFile(
            __DIR__ . '/Assertions/' . $extensionKey . '/Configuration/TCA/Overrides/tx_scheduler_task.php',
            $content
        );
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function provideData(): \Iterator
    {
        yield 'Test that new tx_scheduler_task.php is created with correct content' => ['extension1'];

        yield 'Test that content is appended to existing tx_scheduler_task.php file' => [
            'extension2',
            '<?php' . PHP_EOL . PHP_EOL . '# file exists',
        ];
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
