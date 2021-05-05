<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Composer\ExtensionComposerRector;

use Iterator;
use Nette\Utils\Json;
use Rector\Testing\PHPUnit\AbstractTestCase;
use Ssch\TYPO3Rector\Rector\Composer\ExtensionComposerRector;
use Symplify\ComposerJsonManipulator\ComposerJsonFactory;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\EasyTesting\StaticFixtureSplitter;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ExtensionComposerRectorTest extends AbstractTestCase
{
    /**
     * @var ComposerJsonFactory
     */
    private $composerJsonFactory;

    /**
     * @var ExtensionComposerRector
     */
    private $extensionComposerRector;

    protected function setUp(): void
    {
        $this->bootFromConfigFileInfos([new SmartFileInfo($this->provideConfigFilePath())]);

        $this->extensionComposerRector = $this->getService(ExtensionComposerRector::class);
        $this->composerJsonFactory = $this->getService(ComposerJsonFactory::class);
    }

    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    public function provideData(): Iterator
    {
        return StaticFixtureFinder::yieldDirectory(__DIR__ . '/Fixture', '*.json');
    }

    private function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }

    private function doTestFileInfo(SmartFileInfo $smartFileInfo): void
    {
        $inputFileInfoAndExpected = StaticFixtureSplitter::splitFileInfoToLocalInputAndExpected($smartFileInfo);

        $composerJson = $this->composerJsonFactory->createFromFileInfo($inputFileInfoAndExpected->getInputFileInfo());
        $this->extensionComposerRector->refactor($composerJson);

        $changedComposerJson = Json::encode($composerJson->getJsonArray(), Json::PRETTY);
        $this->assertJsonStringEqualsJsonString($inputFileInfoAndExpected->getExpected(), $changedComposerJson);
    }
}
