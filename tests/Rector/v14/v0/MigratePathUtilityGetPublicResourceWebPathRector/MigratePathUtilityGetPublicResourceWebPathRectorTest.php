<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePathUtilityGetPublicResourceWebPathRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\ValueObject\PhpVersion;

final class MigratePathUtilityGetPublicResourceWebPathRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider providePHP74Data()
     */
    public function testPhp74(string $filePath): void
    {
        if (PHP_VERSION_ID >= PhpVersion::PHP_80) {
            $this->markTestSkipped('Do not execute');
        }

        $this->doTestFile($filePath);
    }

    /**
     * @dataProvider providePHP80Data()
     */
    public function testPhp80(string $filePath): void
    {
        if (PHP_VERSION_ID < PhpVersion::PHP_80 || PHP_VERSION_ID >= PhpVersion::PHP_81) {
            $this->markTestSkipped('Do not execute');
        }

        $this->doTestFile($filePath);
    }

    /**
     * @dataProvider providePHP81Data()
     */
    public function testPhp81(string $filePath): void
    {
        if (PHP_VERSION_ID < PhpVersion::PHP_81) {
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

    /**
     * @return \Iterator<array<string>>
     */
    public static function providePHP81Data(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture/PHP81');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
