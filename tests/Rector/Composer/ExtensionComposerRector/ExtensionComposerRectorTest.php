<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Composer\ExtensionComposerRector;

use Iterator;
use Nette\Utils\Json;
use Rector\Core\HttpKernel\RectorKernel;
use Rector\Testing\Guard\FixtureGuard;
use Ssch\TYPO3Rector\Composer\ComposerModifier;
use Symplify\ComposerJsonManipulator\ComposerJsonFactory;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\EasyTesting\StaticFixtureSplitter;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ExtensionComposerRectorTest extends AbstractKernelTestCase
{
    /**
     * @var FixtureGuard
     */
    private $fixtureGuard;

    /**
     * @var ComposerJsonFactory
     */
    private $composerJsonFactory;

    /**
     * @var ComposerModifier
     */
    private $composerModifier;

    protected function setUp(): void
    {
        $this->bootKernelWithConfigs(RectorKernel::class, [$this->provideConfigFile()]);

        $this->fixtureGuard = $this->getService(FixtureGuard::class);
        $this->composerModifier = $this->getService(ComposerModifier::class);
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

    public function provideConfigFile(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }

    private function doTestFileInfo(SmartFileInfo $smartFileInfo): void
    {
        $this->fixtureGuard->ensureFileInfoHasDifferentBeforeAndAfterContent($smartFileInfo);

        $inputFileInfoAndExpected = StaticFixtureSplitter::splitFileInfoToLocalInputAndExpected($smartFileInfo);

        $composerJson = $this->composerJsonFactory->createFromFileInfo($inputFileInfoAndExpected->getInputFileInfo());
        $this->composerModifier->modify($composerJson);

        $changedComposerJson = Json::encode($composerJson->getJsonArray(), Json::PRETTY);
        $this->assertJsonStringEqualsJsonString($inputFileInfoAndExpected->getExpected(), $changedComposerJson);
    }
}
