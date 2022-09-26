<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\ReplaceExtKeyWithExtensionKeyRector;

use Iterator;
use Rector\Testing\Fixture\FixtureTempFileDumper;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ReplaceExtKeyWithExtensionKeyFromComposerJsonNameRectorTest extends AbstractRectorTestCase
{
    private string $composerJsonFileName;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createTemporaryComposerJsonFile();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unlink($this->composerJsonFileName);
    }

    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return Iterator<array<string>>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(
            __DIR__ . '/Fixture/my_extension_with_composer_json_no_extension_key_defined'
        );
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }

    private function createTemporaryComposerJsonFile(): void
    {
        $composerJsonSmartFileInfo = new SmartFileInfo(
            __DIR__ . '/Fixture/my_extension_with_composer_json_no_extension_key_defined/composer.json'
        );

        $tempComposerJsonFileName = FixtureTempFileDumper::getTempDirectory() . '/composer.json';
        file_put_contents($tempComposerJsonFileName, $composerJsonSmartFileInfo->getContents());
        $this->composerJsonFileName = $tempComposerJsonFileName;
    }
}
