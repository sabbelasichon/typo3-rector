<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RegisterExtbaseTypeConvertersAsServicesRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RegisterExtbaseTypeConvertersAsServicesRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->copyAdditionalFixturesToTemporaryDirectory();

        $this->doTestFile($filePath);

        $addedFilesWithContent = $this->removedAndAddedFilesCollector->getAddedFilesWithContent();

        self::assertStringEqualsFile(
            __DIR__ . '/Fixture/ExpectedMySpecialTypeConverter.php',
            $addedFilesWithContent[1]->getFileContent()
        );

        self::assertStringEqualsFile(
            __DIR__ . '/Fixture/ExpectedServices.yaml',
            $addedFilesWithContent[2]->getFileContent()
        );

        self::assertStringEqualsFile(
            __DIR__ . '/Fixture/ExpectedMySecondSpecialTypeConverter.php',
            $addedFilesWithContent[3]->getFileContent()
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

    private function copyAdditionalFixturesToTemporaryDirectory(): void
    {
        $configurationDirectory = self::getFixtureTempDirectory() . '/Configuration/';
        if (! file_exists($configurationDirectory)) {
            mkdir(self::getFixtureTempDirectory() . '/Configuration/');
        }

        copy(__DIR__ . '/Fixture/Services.yaml', self::getFixtureTempDirectory() . '/Configuration/Services.yaml');
    }
}
