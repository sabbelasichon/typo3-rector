<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Core\Utility;

use Iterator;
use Ssch\TYPO3Rector\Rector\Core\Utility\RefactorRemovedMethodsFromGeneralUtilityRector;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

class RefactorRemovedMethodsFromGeneralUtilityRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [__DIR__ . '/Fixture/remove_generalutility_methods.php.inc'];
    }

    protected function getRectorsWithConfiguration(): array
    {
        return [
            RefactorRemovedMethodsFromGeneralUtilityRector::class => [],
        ];
    }
}
