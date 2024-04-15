<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddPageTSConfigToPageTsConfigFileRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;

final class MigrateAddPageTSConfigToPageTsConfigFileRectorTest extends AbstractRectorTestCase
{
    private FilesystemInterface $filesystem;

    protected function setUp(): void
    {
        parent::setUp();
        $this->initializeFilesystem();
    }

    /**
     * @dataProvider provideData
     */
    public function test(string $extensionFolder, ?string $existingPageTSConfigContent = null): void
    {
        // Arrange
        $this->filesystem->write(__DIR__ . '/Fixture/' . $extensionFolder . '/composer.json', '{
    "extra": {
        "typo3/cms": {
            "extension-key": "cray_special_extension_key"
        }
    }
}
');

        if ($existingPageTSConfigContent !== null) {
            $this->filesystem->write(
                __DIR__ . '/Fixture/' . $extensionFolder . '/Configuration/page.tsconfig',
                $existingPageTSConfigContent
            );
        }

        // Act
        $this->doTestFile(__DIR__ . '/Fixture/' . $extensionFolder . '/ext_localconf.php.inc');

        // Assert
        $content = $this->filesystem->read(__DIR__ . '/Fixture/' . $extensionFolder . '/Configuration/page.tsconfig');
        self::assertStringEqualsFile(__DIR__ . '/Assertions/' . $extensionFolder . '/page.tsconfig', $content);
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }

    /**
     * @return Iterator<array<string>>
     */
    public static function provideData(): Iterator
    {
        yield 'Test that new page.tsconfig is created with correct content' => ['extension1'];

        yield 'Test that content is appended to existing page.tsconfig file' => [
            'extension2',
            '# page.tsconfig file exist',
        ];

        yield 'Test that new page.tsconfig is created with correct content but unresolvable content cannot be migrated properly' => [
            'extension3',
        ];
    }

    private function initializeFilesystem(): void
    {
        $this->filesystem = $this->getContainer()
            ->get(FilesystemInterface::class);
    }
}
