<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\General\Renaming\ConstantsToBackedEnumValueRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\ValueObject\PhpVersionFeature;

final class ConstantsToBackedEnumValueRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        if (PHP_VERSION_ID < PhpVersionFeature::ENUM) {
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
