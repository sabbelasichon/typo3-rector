<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateFileCollectionRegistryAddTypeToTCARector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;

final class MigrateFileCollectionRegistryAddTypeToTCARectorTest extends AbstractRectorTestCase
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
    public function testWithNonExistingComposerJson(string $extensionKey, ?string $existingContent = null): void
    {
        // Arrange
        if ($existingContent !== null) {
            $pageTsConfig = __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/TCA/Overrides/sys_file_collection.php';
            $this->testFilesToDelete[] = $pageTsConfig;
            $this->filesystem->write($pageTsConfig, $existingContent);
        }

        // Act
        $this->doTestFile(__DIR__ . '/Fixture/' . $extensionKey . '/ext_localconf.php.inc');
        $this->testFilesToDelete[] = __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/TCA/Overrides/sys_file_collection.php';

        // Assert
        $content = $this->filesystem->read(
            __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/TCA/Overrides/sys_file_collection.php'
        );
        self::assertStringEqualsFile(
            __DIR__ . '/Assertions/' . $extensionKey . '/Configuration/TCA/Overrides/sys_file_collection.php',
            $content
        );
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function provideData(): \Iterator
    {
        yield 'Test that new page.tsconfig is created with correct content' => ['extension1'];

        yield 'Test that content is appended to existing page.tsconfig file' => [
            'extension2',
            '<?php

// file exists
',
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
