<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Name\RenameClassRector;

use Iterator;
use Rector\Core\Configuration\Option;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\PostRector\NameImportingPostRector;
use Ssch\TYPO3Rector\Tests\Rector\Name\RenameClassRector\Source\NewClass;
use Ssch\TYPO3Rector\Tests\Rector\Name\RenameClassRector\Source\OldClass;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @see NameImportingPostRector
 */
final class AutoImportNamesParameterTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->setParameter(Option::AUTO_IMPORT_NAMES, false);
        $this->setParameter(Typo3Option::AUTO_IMPORT_NAMES, true);

        $this->doTestFileInfo($fileInfo);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureAutoImportNames');
    }

    /**
     * @return array<string, mixed[]>
     */
    protected function getRectorsWithConfiguration(): array
    {
        return [
            RenameClassRector::class => [
                RenameClassRector::OLD_TO_NEW_CLASSES => [
                    OldClass::class => NewClass::class,
                ],
            ],
        ];
    }
}
