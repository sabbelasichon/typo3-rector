<?php

namespace Ssch\TYPO3Rector\Tests\Core\Utility;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Core\Utility\RefactorRemovedMethodsFromGeneralUtilityRector;
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
