<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Resources\Icons\IconsProcessor;

use Ssch\TYPO3Rector\Tests\Application\ApplicationFileProcessor\AbstractApplicationFileProcessorTest;

final class IconsProcessorTest extends AbstractApplicationFileProcessorTest
{
    public function testExtensionWithoutIconInIconsFolder(): void
    {
        $files = $this->fileFactory->createFromPaths([__DIR__ . '/Fixture/my_extension']);
        $this->applicationFileProcessor->run($files);

        $addedFilesWithContent = $this->removedAndAddedFilesCollector->getAddedFilesWithContent();

        $this->assertStringContainsString(
            'Resources/Public/Icons/Extension.gif',
            $addedFilesWithContent[0]->getFilePath()
        );
        $this->assertSame(1, $this->removedAndAddedFilesCollector->getAddedFileCount());

        $this->assertCount(1, $files);
    }

    public function testExtensionWithIconInIconsFolder(): void
    {
        $files = $this->fileFactory->createFromPaths([__DIR__ . '/Fixture/my_extension_with_icon']);
        $this->applicationFileProcessor->run($files);

        $addedFilesWithContent = $this->removedAndAddedFilesCollector->getAddedFilesWithContent();

        $this->assertCount(2, $files);
        $this->assertEmpty($addedFilesWithContent);
    }

    protected function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
