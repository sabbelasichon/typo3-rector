<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Composer\ReplacePackageComposerRector;

use Iterator;
use Rector\Tests\Composer\Rector\AbstractComposerRectorTestCase;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ReplacePackageComposerRectorTest extends AbstractComposerRectorTestCase
{
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
}
