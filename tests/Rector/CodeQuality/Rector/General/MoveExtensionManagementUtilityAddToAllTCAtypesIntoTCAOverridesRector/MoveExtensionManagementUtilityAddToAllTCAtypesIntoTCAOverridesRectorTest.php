<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\Rector\General\MoveExtensionManagementUtilityAddToAllTCAtypesIntoTCAOverridesRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;

class MoveExtensionManagementUtilityAddToAllTCAtypesIntoTCAOverridesRectorTest extends AbstractRectorTestCase
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
        ?string $existingSysTemplateContent = null
    ): void {
        // Arrange
        if ($existingSysTemplateContent !== null) {
            $sysTemplate = __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/TCA/Overrides/tt_content.php';
            $this->testFilesToDelete[] = $sysTemplate;
            $this->filesystem->write($sysTemplate, $existingSysTemplateContent);
        }

        // Act
        $this->doTestFile(__DIR__ . '/Fixture/' . $extensionKey . '/ext_tables.php.inc');
        $this->testFilesToDelete[] = __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/TCA/Overrides/tt_content.php';

        // Assert
        $content = $this->filesystem->read(
            __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/TCA/Overrides/tt_content.php'
        );
        self::assertStringEqualsFile(
            __DIR__ . '/Assertions/' . $extensionKey . '/Configuration/TCA/Overrides/tt_content.php',
            $content
        );
    }

    /**
     * @return Iterator<array<string>>
     */
    public static function provideData(): Iterator
    {
        yield 'Test that new tt_content.php is created with correct content' => ['extension1'];

        yield 'Test that content is appended to existing tt_content.php file' => [
            'extension2',
            '<?php

# tt_content.php file exists
',
        ];

        yield 'Test that new tt_content.php is created with correct content but unresolvable content cannot be migrated properly' => [
            'extension3',
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
