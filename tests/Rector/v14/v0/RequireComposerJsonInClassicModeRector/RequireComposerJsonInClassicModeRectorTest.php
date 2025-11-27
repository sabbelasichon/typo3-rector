<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RequireComposerJsonInClassicModeRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;

final class RequireComposerJsonInClassicModeRectorTest extends AbstractRectorTestCase
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
        ?string $existingComposerJsonContent = null
    ): void {
        // Arrange
        if ($existingComposerJsonContent !== null) {
            $composerJson = __DIR__ . '/Fixture/' . $extensionKey . '/composer.json';
            $this->testFilesToDelete[] = $composerJson;
            $this->filesystem->write($composerJson, $existingComposerJsonContent);
        }

        // Act
        $this->doTestFile(__DIR__ . '/Fixture/' . $extensionKey . '/ext_emconf.php.inc');
        $this->testFilesToDelete[] = __DIR__ . '/Fixture/' . $extensionKey . '/composer.json';

        // Assert
        $content = $this->filesystem->read(__DIR__ . '/Fixture/' . $extensionKey . '/composer.json');
        self::assertStringEqualsFile(__DIR__ . '/Assertions/' . $extensionKey . '/composer.json', $content);
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function provideData(): \Iterator
    {
        #yield 'Test that new composer.json is created with correct content' => ['extension1'];
        #yield 'Test existing composer.json file is not touched' => ['extension2', '{}' . PHP_EOL];
        yield 'Test extension key is given in ext_emconf.php' => ['extension3'];
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
