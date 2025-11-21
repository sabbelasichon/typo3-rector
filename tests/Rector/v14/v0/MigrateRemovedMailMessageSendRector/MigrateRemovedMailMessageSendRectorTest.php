<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateRemovedMailMessageSendRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\ValueObject\PhpVersionFeature;

final class MigrateRemovedMailMessageSendRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider providePHP74Data()
     */
    public function testPhp74(string $filePath): void
    {
        if (PHP_VERSION_ID >= PhpVersionFeature::PROPERTY_PROMOTION) {
            $this->markTestSkipped('Do not execute');
        }

        $this->doTestFile($filePath);
    }

    /**
     * @dataProvider providePHP80Data()
     */
    public function testPhp80(string $filePath): void
    {
        if (PHP_VERSION_ID < PhpVersionFeature::PROPERTY_PROMOTION || PHP_VERSION_ID >= PhpVersionFeature::READONLY_PROPERTY) {
            $this->markTestSkipped('Do not execute');
        }

        $this->doTestFile($filePath);
    }

    /**
     * @dataProvider providePHP81Data()
     */
    public function testPhp81(string $filePath): void
    {
        if (PHP_VERSION_ID < PhpVersionFeature::READONLY_PROPERTY) {
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
