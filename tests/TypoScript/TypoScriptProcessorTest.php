<?php

namespace Ssch\TYPO3Rector\Tests\TypoScript;

use Iterator;
use Rector\Composer\Tests\Contract\ConfigFileAwareInterface;
use Rector\Core\HttpKernel\RectorKernel;
use Rector\Testing\Guard\FixtureGuard;
use Ssch\TYPO3Rector\TypoScript\TypoScriptProcessor;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\EasyTesting\StaticFixtureSplitter;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class TypoScriptProcessorTest extends AbstractKernelTestCase implements ConfigFileAwareInterface
{
    /**
     * @var TypoScriptProcessor
     */
    private $typoScriptProcessor;

    /**
     * @var FixtureGuard
     */
    private $fixtureGuard;

    protected function setUp(): void
    {
        $this->bootKernelWithConfigs(RectorKernel::class, [$this->provideConfigFile()]);
        $this->typoScriptProcessor = $this->getService(TypoScriptProcessor::class);
        $this->fixtureGuard = $this->getService(FixtureGuard::class);
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
        return StaticFixtureFinder::yieldDirectory(__DIR__ . '/Fixture', '*.typoscript');
    }

    public function provideConfigFile(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }

    private function doTestFileInfo(SmartFileInfo $smartFileInfo): void
    {
        $this->fixtureGuard->ensureFileInfoHasDifferentBeforeAndAfterContent($smartFileInfo);

        $inputFileInfoAndExpected = StaticFixtureSplitter::splitFileInfoToLocalInputAndExpected($smartFileInfo);

        $this->typoScriptProcessor->process($smartFileInfo->getRealPath());
        $this->assertSame($inputFileInfoAndExpected->getExpected(), $changedTypoScript);
    }
}
