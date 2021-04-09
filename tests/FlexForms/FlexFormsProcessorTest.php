<?php

namespace Ssch\TYPO3Rector\Tests\FlexForms;

use Iterator;
use Rector\Core\HttpKernel\RectorKernel;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\FlexForms\FlexFormsProcessor;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\EasyTesting\StaticFixtureSplitter;
use Symplify\SmartFileSystem\SmartFileInfo;

final class FlexFormsProcessorTest extends AbstractRectorTestCase
{
    /**
     * @var FlexFormsProcessor
     */
    private $flexformsProcessor;

    protected function setUp(): void
    {
        $this->bootKernelWithConfigs(RectorKernel::class, [$this->provideConfigFile()]);
        $this->flexformsProcessor = $this->getService(FlexFormsProcessor::class);
    }

    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $smartFileInfo): void
    {
        $inputFileInfoAndExpected = StaticFixtureSplitter::splitFileInfoToLocalInputAndExpected($smartFileInfo);

        $changedXml = $this->flexformsProcessor->process($inputFileInfoAndExpected->getInputFileInfo());

        $newContent = '';
        if (null !== $changedXml) {
            $newContent = $changedXml->getNewContent();
        }

        $this->assertSame($inputFileInfoAndExpected->getExpected(), $newContent);
    }

    public function provideData(): Iterator
    {
        return StaticFixtureFinder::yieldDirectory(__DIR__ . '/Fixture', '*.xml');
    }

    public function provideConfigFile(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
