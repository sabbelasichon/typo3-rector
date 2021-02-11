<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\ReplaceExtKeyWithExtensionKey;

use Iterator;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ReplaceExtKeyWithExtensionKeyRectorTest extends \Rector\Testing\PHPUnit\AbstractCommunityRectorTestCase
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

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
