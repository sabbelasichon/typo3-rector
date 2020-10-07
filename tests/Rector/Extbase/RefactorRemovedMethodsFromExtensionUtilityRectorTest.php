<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Extbase;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RefactorRemovedMethodsFromExtensionUtilityRectorTest extends AbstractRectorWithConfigTestCase
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
        return yield [new SmartFileInfo(__DIR__ . '/Fixture/removed_methods_from_extension_utility.php.inc')];
    }
}
