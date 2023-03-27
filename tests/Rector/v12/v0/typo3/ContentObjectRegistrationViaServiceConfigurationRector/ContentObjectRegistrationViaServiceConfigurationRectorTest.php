<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ContentObjectRegistrationViaServiceConfigurationRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ContentObjectRegistrationViaServiceConfigurationRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);

        $addedFilesWithContent = $this->removedAndAddedFilesCollector->getAddedFilesWithContent();

        self::assertStringEqualsFile(
            __DIR__ . '/Fixture/ExpectedServices.yaml',
            $addedFilesWithContent[1]->getFileContent()
        );
    }

    /**
     * @return Iterator<array<string>>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
