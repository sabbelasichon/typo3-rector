<?php

declare(strict_types=1);

namespace Rector\CodeQuality\General\RemoveTypo3VersionChecksRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RemoveTypo3VersionChecksRectorTest extends AbstractRectorTestCase
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
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
