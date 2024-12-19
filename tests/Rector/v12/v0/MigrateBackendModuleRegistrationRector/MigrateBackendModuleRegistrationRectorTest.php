<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateBackendModuleRegistrationRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;

final class MigrateBackendModuleRegistrationRectorTest extends AbstractRectorTestCase
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
        ?string $existingModulesContent = null
    ): void {
        // Arrange
        if ($existingModulesContent !== null) {
            $sysTemplate = __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/Backend/Modules.php';
            $this->testFilesToDelete[] = $sysTemplate;
            $this->filesystem->write($sysTemplate, $existingModulesContent);
        }

        // Act
        $this->doTestFile(__DIR__ . '/Fixture/' . $extensionKey . '/ext_tables.php.inc');
        $this->testFilesToDelete[] = __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/Backend/Modules.php';

        // Assert
        $content = $this->filesystem->read(
            __DIR__ . '/Fixture/' . $extensionKey . '/Configuration/Backend/Modules.php'
        );
        self::assertStringEqualsFile(
            __DIR__ . '/Assertions/' . $extensionKey . '/Configuration/Backend/Modules.php',
            $content
        );
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function provideData(): \Iterator
    {
        yield 'Test that new Modules.php is created with correct content EXT:extension1' => ['extension1'];
        yield 'Test that new Modules.php is created with correct content EXT:extension_empty_access' => ['extension_empty_access'];
        yield 'Test that new Modules.php is created with correct content EXT:install' => ['install'];
        yield 'Test that new Modules.php is created with correct content EXT:setup' => ['setup'];
        yield 'Test that new Modules.php is created with correct content EXT:viewpage' => ['viewpage'];
        yield 'Test that new Modules.php is created with correct content EXT:extbase' => ['extbase'];
        yield 'Test that new Modules.php is created with correct content EXT:beuser' => ['beuser'];
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
