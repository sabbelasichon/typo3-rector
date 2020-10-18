<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v10\v3\UseClassTypo3VersionRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class SubstituteDeprecatedRecordHistoryMethodsRectorTest extends AbstractRectorTestCase
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
        yield [new SmartFileInfo(__DIR__ . '/Fixture/substitute_deprectated_record_history_methds.php.inc')];
    }

    protected function getRectorClass(): string
    {
        return UseClassTypo3VersionRector::class;
    }
}
