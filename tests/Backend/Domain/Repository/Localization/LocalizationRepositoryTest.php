<?php

namespace Ssch\TYPO3Rector\Tests\Backend\Domain\Repository\Localization;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

class LocalizationRepositoryTest extends AbstractRectorWithConfigTestCase
{
    /**
     * @dataProvider provideDataForTest()
     *
     * @param string $file
     */
    public function test(string $file): void
    {
        $this->doTestFile($file);
    }

    public function provideDataForTest(): Iterator
    {
        yield [__DIR__ . '/Fixture/remove_colpos_parameter_localizationrepository_methods.php.inc'];
    }
}
