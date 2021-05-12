<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Resources\Icons\IconsProcessor;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class IconsProcessorTest extends AbstractRectorTestCase
{
    public function test(): void
    {
        $this->assertSame(0, $this->removedAndAddedFilesCollector->getAddedFileCount());
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
