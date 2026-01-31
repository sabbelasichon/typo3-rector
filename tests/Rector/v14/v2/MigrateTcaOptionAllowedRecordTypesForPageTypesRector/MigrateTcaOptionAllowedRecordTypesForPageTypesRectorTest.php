<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateTcaOptionAllowedRecordTypesForPageTypesRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;

final class MigrateTcaOptionAllowedRecordTypesForPageTypesRectorTest extends AbstractRectorTestCase
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
    public function testWithNonExistingComposerJson(string $extensionKey, ?string $existingModulesContent = null): void
    {
        // Arrange
        if ($existingModulesContent !== null) {
            $sysTemplate = __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/TCA/Overrides/pages.php';
            $this->testFilesToDelete[] = $sysTemplate;
            $this->filesystem->write($sysTemplate, $existingModulesContent);
        }

        // Act
        $this->doTestFile(__DIR__ . '/Fixture/' . $extensionKey . '/ext_tables.php.inc');
        $this->testFilesToDelete[] = __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/TCA/Overrides/pages.php';

        // Assert
        $content = $this->filesystem->read(
            __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/TCA/Overrides/pages.php'
        );
        self::assertStringEqualsFile(
            __DIR__ . '/Assertions/' . $extensionKey . '/Configuration/TCA/Overrides/pages.php',
            $content
        );
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function provideData(): \Iterator
    {
        yield 'Test that new pages.php is created with correct content with asterisk' => ['extension1'];
        yield 'Test that new pages.php is created with correct content with array values' => ['extension2'];
        yield 'Test that new content is appended to existing pages.php' => [
            'extension3',
            <<<'EOF'
<?php

declare(strict_types=1);

// existing content
EOF
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
