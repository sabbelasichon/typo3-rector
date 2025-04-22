<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v1\RenamePageTreeNavigationComponentIdRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RenamePageTreeNavigationComponentIdRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $extensionKey): void
    {
        $this->doTestFile(__DIR__ . '/Fixture/' . $extensionKey . '/Configuration/Backend/Modules.php.inc');
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function provideData(): \Iterator
    {
        yield 'Extension1' => ['extension1'];
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
