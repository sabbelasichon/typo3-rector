<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Extbase\Utility;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

final class UseNativePhpHex2binMethodRectorTest extends AbstractRectorWithConfigTestCase
{
    /**
     * @dataProvider provideDataForTest()
     */
    public function test(string $file): void
    {
        $this->doTestFile($file);
    }

    public function provideDataForTest(): Iterator
    {
        yield [__DIR__ . '/Fixture/native_hex2bin.php.inc'];
    }
}
