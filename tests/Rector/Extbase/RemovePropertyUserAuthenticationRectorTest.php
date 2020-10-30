<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Extbase;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v8\v0\RemovePropertyUserAuthenticationRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RemovePropertyUserAuthenticationRectorTest extends AbstractRectorTestCase
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
        yield [new SmartFileInfo(__DIR__ . '/Fixture/remove_user_authentication_property.php.inc')];
    }

    protected function getRectorClass(): string
    {
        return RemovePropertyUserAuthenticationRector::class;
    }
}
