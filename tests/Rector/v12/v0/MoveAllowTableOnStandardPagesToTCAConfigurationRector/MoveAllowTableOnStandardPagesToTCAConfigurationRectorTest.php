<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MoveAllowTableOnStandardPagesToTCAConfigurationRector;

use Nette\Utils\FileSystem;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;

final class MoveAllowTableOnStandardPagesToTCAConfigurationRectorTest extends AbstractRectorTestCase
{
    private FilesystemInterface $filesystem;

    protected function setUp(): void
    {
        parent::setUp();
        $this->initializeFilesystem();
        $this->registerFileToFilesystem(
            __DIR__ . '/Fixture/Configuration/TCA/tx_table_with_existing_tca_configuration_file.php'
        );
        $this->registerFileToFilesystem(
            __DIR__ . '/Fixture/Configuration/TCA/tx_table_with_existing_tca_configuration_file_and_security_key.php'
        );
    }

    public function test(): void
    {
        $this->doTestFile(__DIR__ . '/Fixture/ext_tables.php.inc');

        $this->assertThatConfigurationFileHasNewIgnorePageTypeRestriction(
            __DIR__ . '/Fixture/Configuration/TCA/%s.php',
            'tx_table_with_existing_tca_configuration_file'
        );
        $this->assertThatConfigurationFileHasNewIgnorePageTypeRestriction(
            __DIR__ . '/Fixture/Configuration/TCA/%s.php',
            'tx_table_with_existing_tca_configuration_file_and_security_key'
        );
        $this->assertThatConfigurationFileHasNewIgnorePageTypeRestriction(
            __DIR__ . '/Fixture/Configuration/TCA/Overrides/%s.php',
            'tx_table_without_existing_tca_configuration_file'
        );
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }

    private function assertThatConfigurationFileHasNewIgnorePageTypeRestriction(
        string $pathToConfigurationFile,
        string $tableName
    ): void {
        $pathToConfigurationFile = sprintf($pathToConfigurationFile, $tableName);
        $contents = $this->filesystem->read($pathToConfigurationFile);
        $expectedFile = sprintf(__DIR__ . '/Assertions/%s.php.inc', $tableName);
        self::assertStringEqualsFile($expectedFile, $contents);
    }

    private function registerFileToFilesystem(string $pathToFile): void
    {
        $this->filesystem->write($pathToFile, FileSystem::read($pathToFile));
    }

    private function initializeFilesystem(): void
    {
        $this->filesystem = $this->getContainer()
            ->get(FilesystemInterface::class);
    }
}
