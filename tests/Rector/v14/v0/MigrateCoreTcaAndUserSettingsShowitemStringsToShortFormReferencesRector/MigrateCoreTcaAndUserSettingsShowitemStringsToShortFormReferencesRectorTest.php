<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateCoreTcaAndUserSettingsShowitemStringsToShortFormReferencesRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class MigrateCoreTcaAndUserSettingsShowitemStringsToShortFormReferencesRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideCoreData()
     */
    public function testCore(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function provideCoreData(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/core');
    }

    /**
     * @dataProvider provideFileMetadataData()
     */
    public function testFileMetadata(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function provideFileMetadataData(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/filemetadata');
    }

    /**
     * @dataProvider provideFrontendData()
     */
    public function testFrontend(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function provideFrontendData(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/frontend');
    }

    /**
     * @dataProvider provideRedirectsData()
     */
    public function testRedirects(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function provideRedirectsData(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/redirects');
    }

    /**
     * @dataProvider provideWebhooksData()
     */
    public function testWebhooks(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function provideWebhooksData(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/webhooks');
    }

    /**
     * @dataProvider provideWorkspacesData()
     */
    public function testWorkspaces(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function provideWorkspacesData(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/workspaces');
    }

    /**
     * @dataProvider providePaletteData()
     */
    public function testPalette(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function providePaletteData(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Palette');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
