<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddUserTSConfigToUserTsConfigFileRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;

final class MigrateAddUserTSConfigToUserTsConfigFileRectorTest extends AbstractRectorTestCase
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
        ?string $existingUserTSConfigContent = null
    ): void {
        // Arrange
        if ($existingUserTSConfigContent !== null) {
            $userTsConfig = __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/user.tsconfig';
            $this->testFilesToDelete[] = $userTsConfig;
            $this->filesystem->write($userTsConfig, $existingUserTSConfigContent);
        }

        // Act
        $this->doTestFile(__DIR__ . '/Fixture/' . $extensionKey . '/ext_localconf.php.inc');
        $this->testFilesToDelete[] = __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/user.tsconfig';

        // Assert
        $content = $this->filesystem->read(__DIR__ . '/Fixture/' . $extensionKey . '/Configuration/user.tsconfig');
        self::assertStringEqualsFile(
            __DIR__ . '/Assertions/' . $extensionKey . '/Configuration/user.tsconfig',
            $content
        );
    }

    /**
     * @dataProvider provideData
     */
    public function testWithExistingComposerJson(
        string $extensionKey,
        ?string $existingUserTSConfigContent = null
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

        if ($existingUserTSConfigContent !== null) {
            $userTsConfig = __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/user.tsconfig';
            $this->testFilesToDelete[] = $userTsConfig;
            $this->filesystem->write($userTsConfig, $existingUserTSConfigContent);
        }

        // Act
        $this->doTestFile(__DIR__ . '/Fixture/' . $extensionKey . '/ext_localconf.php.inc');
        $this->testFilesToDelete[] = __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/user.tsconfig';

        // Assert
        $content = $this->filesystem->read(__DIR__ . '/Fixture/' . $extensionKey . '/Configuration/user.tsconfig');
        self::assertStringEqualsFile(
            __DIR__ . '/Assertions/' . $extensionKey . '/Configuration/user.tsconfig',
            $content
        );
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function provideData(): \Iterator
    {
        yield 'Test that new user.tsconfig is created with correct content' => ['extension1'];

        yield 'Test that content is appended to existing user.tsconfig file' => [
            'extension2',
            '# user.tsconfig file exists',
        ];

        yield 'Test that new user.tsconfig is created with correct content but unresolvable content cannot be migrated properly' => [
            'extension3',
        ];

        yield 'Test that new user.tsconfig is not created without importing itself again' => [
            'no_recursion',
            '# user.tsconfig file exists' . PHP_EOL,
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
