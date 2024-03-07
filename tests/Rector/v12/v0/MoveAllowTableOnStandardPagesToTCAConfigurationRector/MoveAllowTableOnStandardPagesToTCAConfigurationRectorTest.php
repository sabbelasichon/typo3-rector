<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MoveAllowTableOnStandardPagesToTCAConfigurationRector;

use Nette\Utils\FileSystem;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class MoveAllowTableOnStandardPagesToTCAConfigurationRectorTest extends AbstractRectorTestCase
{
    /**
     * @var array<int, string>
     */
    private array $filesToDelete = [];

    protected function tearDown(): void
    {
        parent::tearDown();
        foreach ($this->filesToDelete as $fileToDelete) {
            FileSystem::delete($fileToDelete);
        }
    }

    public function test(): void
    {
        $this->doTestFile(__DIR__ . '/Fixture/ext_tables.php.inc');
        $this->assertThatConfigurationFileHasNewIgnorePageTypeRestriction(
            __DIR__ . '/Fixture/Configuration/TCA/%s.php.inc',
            'tx_table_with_existing_tca_configuration_file'
        );
        $this->assertThatConfigurationFileHasNewIgnorePageTypeRestriction(
            __DIR__ . '/Fixture/Configuration/TCA/%s.php.inc',
            'tx_table_with_existing_tca_configuration_file_and_security_key'
        );
        $this->assertThatConfigurationFileHasNewIgnorePageTypeRestriction(
            __DIR__ . '/Fixture/Configuration/TCA/Overrides/%s.php.inc',
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
        $expectedFile = sprintf(__DIR__ . '/Assertions/%s.php.inc', $tableName);
        self::assertStringEqualsFile($expectedFile, FileSystem::read($pathToConfigurationFile));

        $this->filesToDelete[] = $pathToConfigurationFile;
    }
}
