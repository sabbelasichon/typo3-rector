<?php

declare(strict_types=1);

namespace Rector\v13\v0\StrictTypesFolderInterfaceRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class StrictTypesFolderInterfaceRector extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return Iterator<array<string>>
     */
    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
