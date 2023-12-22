<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\typoscript\MigrateXhtmlDoctypeRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class MigrateXhtmlDoctypeRectorTest extends AbstractRectorTestCase
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
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture', '*.typoscript.inc');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
