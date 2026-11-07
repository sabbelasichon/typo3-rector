<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\AddRequestArgumentToPageRendererMethodsRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class AddRequestArgumentToPageRendererMethodsRectorTest extends AbstractRectorTestCase
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
