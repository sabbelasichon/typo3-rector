<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Migrations;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RenameClassMapAliasRectorTest extends AbstractRectorTestCase
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

    protected function provideConfigFileInfo(): ?SmartFileInfo
    {
        return new SmartFileInfo(__DIR__ . '/config/configured_rule.php');
    }
}
