<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ExtbaseAnnotationToAttributeRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\ValueObject\PhpVersionFeature;

final class ExtbaseAnnotationToAttributeRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        if (PHP_VERSION_ID < PhpVersionFeature::ATTRIBUTES) {
            $this->markTestSkipped('Do not execute');
        }

        $this->doTestFile($filePath);
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function provideData(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
