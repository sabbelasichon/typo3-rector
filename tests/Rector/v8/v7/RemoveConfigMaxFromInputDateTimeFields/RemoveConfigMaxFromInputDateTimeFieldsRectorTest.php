<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v7\RemoveConfigMaxFromInputDateTimeFields;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v8\v7\RemoveConfigMaxFromInputDateTimeFieldsRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RemoveConfigMaxFromInputDateTimeFieldsRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function getRectorClass(): string
    {
        return RemoveConfigMaxFromInputDateTimeFieldsRector::class;
    }
}
