<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v4\RegisterIconToIconFileRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;

final class RegisterIconToIconFileRectorTest extends AbstractRectorTestCase
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
            $pageTsConfig = __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/Icons.php';
            $this->testFilesToDelete[] = $pageTsConfig;
            $this->filesystem->write($pageTsConfig, $existingContent);
        }

        // Act
        $this->doTestFile(__DIR__ . '/Fixture/' . $extensionKey . '/ext_localconf.php.inc');
        $this->testFilesToDelete[] = __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/Icons.php';

        // Assert
        $content = $this->filesystem->read(__DIR__ . '/Fixture/' . $extensionKey . '/Configuration/Icons.php');
        self::assertStringEqualsFile(
            __DIR__ . '/Assertions/' . $extensionKey . '/Configuration/Icons.php',
            $content
        );
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function provideData(): \Iterator
    {
        yield 'Test that new Icons.php is created with correct content' => ['extension1'];

        yield 'Test that content is appended to existing Icons.php file' => [
            'extension2',
            <<<'CODE'
<?php

return [
    'existing-icon' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:my_extension/Resources/Public/Icons/existing.svg',
    ],
];
CODE
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
