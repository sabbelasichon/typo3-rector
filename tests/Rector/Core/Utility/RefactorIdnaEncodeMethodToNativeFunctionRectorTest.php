<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Core\Utility;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v10\v0\RefactorIdnaEncodeMethodToNativeFunctionRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RefactorIdnaEncodeMethodToNativeFunctionRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideDataForTest()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    public function provideDataForTest(): Iterator
    {
        yield [new SmartFileInfo(__DIR__ . '/Fixture/idna_convert_to_idn_to_ascii.php.inc')];
    }

    protected function getRectorClass(): string
    {
        return RefactorIdnaEncodeMethodToNativeFunctionRector::class;
    }
}
