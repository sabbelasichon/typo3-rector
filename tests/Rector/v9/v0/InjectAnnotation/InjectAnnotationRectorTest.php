<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotation;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class InjectAnnotationRectorTest extends AbstractRectorTestCase
{
    protected function setUp(): void
    {
        $this->markTestSkipped('Due to an small error in the PhpDocInfoPrinter');
    }

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

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
