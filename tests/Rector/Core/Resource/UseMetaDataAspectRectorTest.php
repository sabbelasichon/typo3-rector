<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Core\Resource;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

final class UseMetaDataAspectRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [__DIR__ . '/Fixture/use_metadata_aspect.php.inc'];
    }
}
