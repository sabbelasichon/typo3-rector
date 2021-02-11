<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\General\ConvertTypo3ConfVars;

use Iterator;
use Rector\Testing\PHPUnit\AbstractCommunityRectorTestCase;
use Ssch\TYPO3Rector\Rector\General\ConvertTypo3ConfVarsRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ConvertTypo3ConfVarsRectorTest extends \Rector\Testing\PHPUnit\AbstractCommunityRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
