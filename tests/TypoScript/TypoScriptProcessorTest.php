<?php

namespace Ssch\TYPO3Rector\Tests\TypoScript;

use Iterator;
use Rector\Core\HttpKernel\RectorKernel;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\TypoScript\TypoScriptProcessor;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\EasyTesting\StaticFixtureSplitter;
use Symplify\SmartFileSystem\SmartFileInfo;

final class TypoScriptProcessorTest extends AbstractRectorTestCase
{
    /**
     * @var TypoScriptProcessor
     */
    private $typoScriptProcessor;

    protected function setUp(): void
    {
        $this->bootKernelWithConfigs(RectorKernel::class, [$this->provideConfigFile()]);
        $this->typoScriptProcessor = $this->getService(TypoScriptProcessor::class);
    }

    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $smartFileInfo): void
    {
        $inputFileInfoAndExpected = StaticFixtureSplitter::splitFileInfoToLocalInputAndExpected($smartFileInfo);

        $changedTypoScript = $this->typoScriptProcessor->process($inputFileInfoAndExpected->getInputFileInfo());
        $this->assertSame($inputFileInfoAndExpected->getExpected(), $changedTypoScript);
    }

    public function provideData(): Iterator
    {
        return StaticFixtureFinder::yieldDirectory(__DIR__ . '/Fixture', '*.typoscript');
    }

    public function provideConfigFile(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
