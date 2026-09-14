<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v3\UseSelectItemInsteadOfArrayRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\ValueObject\PhpVersionFeature;

final class UseSelectItemInsteadOfArrayRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider providePHP74Data()
     */
    public function testPhp74(string $filePath): void
    {
        if (PHP_VERSION_ID >= PhpVersionFeature::NAMED_ARGUMENTS) {
            $this->markTestSkipped('Do not execute');
        }

        $this->doTestFile($filePath);
    }

    /**
     * @dataProvider providePHP80Data()
     */
    public function testPhp80(string $filePath): void
    {
        if (PHP_VERSION_ID < PhpVersionFeature::NAMED_ARGUMENTS) {
            $this->markTestSkipped('Do not execute');
        }

        $this->doTestFile($filePath);
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function providePHP74Data(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture/PHP74');
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function providePHP80Data(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture/PHP80');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
