<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Core\Environment;

use Iterator;
use Ssch\TYPO3Rector\Rector\Core\Environment\ConstantToEnvironmentCallRector;
use Ssch\TYPO3Rector\Rector\Core\Environment\RenameMethodCallToEnvironmentMethodCallRector;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class EnvironmentApiTest extends AbstractRectorWithConfigTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function getRectorsWithConfiguration(): array
    {
        return [
            ConstantToEnvironmentCallRector::class => [],
            RenameMethodCallToEnvironmentMethodCallRector::class => [],
        ];
    }
}
