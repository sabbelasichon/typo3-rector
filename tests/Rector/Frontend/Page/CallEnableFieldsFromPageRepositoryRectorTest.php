<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Frontend\Page;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

final class CallEnableFieldsFromPageRepositoryRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [__DIR__ . '/Fixture/call_enable_fields_from_page_repository.php.inc'];
    }
}
