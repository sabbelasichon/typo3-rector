<?php

namespace Ssch\TYPO3Rector\Tests\Yaml\Form;

use Iterator;
use Rector\Core\HttpKernel\RectorKernel;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Yaml\Form\FormYamlProcessor;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\EasyTesting\StaticFixtureSplitter;
use Symplify\SmartFileSystem\SmartFileInfo;

final class FormYamlProcessorTest extends AbstractRectorTestCase
{
    /**
     * @var FormYamlProcessor
     */
    private $formYamlProcessor;

    protected function setUp(): void
    {
        $this->bootKernelWithConfigs(RectorKernel::class, [$this->provideConfigFile()]);
        $this->formYamlProcessor = $this->getService(FormYamlProcessor::class);
    }

    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $inputFileInfoAndExpected = StaticFixtureSplitter::splitFileInfoToLocalInputAndExpected($smartFileInfo);

        $changedTypoScript = $this->formYamlProcessor->process($inputFileInfoAndExpected->getInputFileInfo());
        $this->assertSame($inputFileInfoAndExpected->getExpected(), $changedTypoScript);
    }

    public function provideData(): Iterator
    {
        return StaticFixtureFinder::yieldDirectory(__DIR__ . '/Fixture', '*.form.yaml');
    }

    public function provideConfigFile(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
