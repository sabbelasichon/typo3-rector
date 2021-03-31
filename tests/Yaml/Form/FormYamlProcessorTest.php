<?php

namespace Ssch\TYPO3Rector\Tests\Yaml\Form;

use Iterator;
use Rector\Composer\Tests\Contract\ConfigFileAwareInterface;
use Rector\Core\HttpKernel\RectorKernel;
use Rector\Testing\Guard\FixtureGuard;
use Ssch\TYPO3Rector\Yaml\Form\FormYamlProcessor;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\EasyTesting\StaticFixtureSplitter;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class FormYamlProcessorTest extends AbstractKernelTestCase implements ConfigFileAwareInterface
{
    /**
     * @var FormYamlProcessor
     */
    private $formYamlProcessor;

    /**
     * @var FixtureGuard
     */
    private $fixtureGuard;

    protected function setUp(): void
    {
        $this->bootKernelWithConfigs(RectorKernel::class, [$this->provideConfigFile()]);
        $this->formYamlProcessor = $this->getService(FormYamlProcessor::class);
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
        return StaticFixtureFinder::yieldDirectory(__DIR__ . '/Fixture', '*.form.yaml');
    }

    public function provideConfigFile(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }

    private function doTestFileInfo(SmartFileInfo $smartFileInfo): void
    {
        $this->fixtureGuard->ensureFileInfoHasDifferentBeforeAndAfterContent($smartFileInfo);

        $inputFileInfoAndExpected = StaticFixtureSplitter::splitFileInfoToLocalInputAndExpected($smartFileInfo);

        $changedTypoScript = $this->formYamlProcessor->process($inputFileInfoAndExpected->getInputFileInfo());
        $this->assertSame($inputFileInfoAndExpected->getExpected(), $changedTypoScript);
    }
}
