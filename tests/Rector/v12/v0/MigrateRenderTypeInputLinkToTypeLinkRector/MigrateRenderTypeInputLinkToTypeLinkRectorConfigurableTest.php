<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateRenderTypeInputLinkToTypeLinkRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class MigrateRenderTypeInputLinkToTypeLinkRectorConfigurableTest extends AbstractRectorTestCase
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
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture_configurable');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule_configurable.php';
    }
}
