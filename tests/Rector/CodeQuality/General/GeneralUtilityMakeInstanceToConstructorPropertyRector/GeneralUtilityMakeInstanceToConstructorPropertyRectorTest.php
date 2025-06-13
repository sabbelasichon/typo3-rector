<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\ValueObject\PhpVersionFeature;

final class GeneralUtilityMakeInstanceToConstructorPropertyRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function provideData(): \Iterator
    {
        if (PHP_VERSION >= PhpVersionFeature::PROPERTY_PROMOTION) {
            return self::yieldFilesFromDirectory(__DIR__ . '/Fixture/PHP80');
        }

        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture/PHP74');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
