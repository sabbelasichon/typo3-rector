<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\ReplaceAnnotation;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v9\v0\ReplaceAnnotationRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ReplaceAnnotationRectorTest extends AbstractRectorTestCase
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
            ReplaceAnnotationRector::class => [
                ReplaceAnnotationRector::OLD_TO_NEW_ANNOTATIONS => [
                    'lazy' => 'TYPO3\CMS\Extbase\Annotation\ORM\Lazy',
                    'cascade' => 'TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")',
                    'transient' => 'TYPO3\CMS\Extbase\Annotation\ORM\Transient',
                ],
            ],
        ];
    }
}
