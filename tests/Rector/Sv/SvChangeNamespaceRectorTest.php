<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Migrations;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

class SvChangeNamespaceRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [__DIR__ . '/Fixture/sv_change_namespace.php.inc'];
    }
}
