<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Core\Environment;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v9\v2\RenameMethodCallToEnvironmentMethodCallRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class MethodCallsToEnvironmentApiCallRectorTest extends AbstractRectorTestCase
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
        yield [new SmartFileInfo(__DIR__ . '/Fixture/methods_to_environment_api_calls.php.inc')];
    }

    protected function getRectorClass(): string
    {
        return RenameMethodCallToEnvironmentMethodCallRector::class;
    }
}
