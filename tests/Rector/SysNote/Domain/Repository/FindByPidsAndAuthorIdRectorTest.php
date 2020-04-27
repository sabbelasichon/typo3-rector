<?php

namespace Ssch\TYPO3Rector\Tests\Rector\SysNote\Domain\Repository;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

class FindByPidsAndAuthorIdRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [__DIR__ . '/Fixture/sys_repository_find_by_pids_and_author.php.inc'];
    }
}
