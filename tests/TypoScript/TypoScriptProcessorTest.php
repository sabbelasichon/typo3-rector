<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\TypoScript;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\SmartFileSystem\SmartFileInfo;

final class TypoScriptProcessorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }

    /**
     * @return Iterator<SmartFileInfo>
     */
    public function provideData(): Iterator
    {
        return StaticFixtureFinder::yieldDirectory(__DIR__ . '/Fixture', '*.typoscript');
    }
}
