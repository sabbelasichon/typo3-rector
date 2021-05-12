<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\FlexForms;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\SmartFileSystem\SmartFileInfo;

final class FlexFormsProcessorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    /**
     * @return Iterator<SmartFileInfo>
     */
    public function provideData(): Iterator
    {
        return StaticFixtureFinder::yieldDirectory(__DIR__ . '/Fixture', '*.xml');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
