<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Fluid;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\FileProcessor\Fluid\Rector\FluidRectorInterface;

/**
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\Fluid\FluidProcessorTest
 */
final class FluidProcessor implements FileProcessorInterface
{
    /**
     * @param FluidRectorInterface[] $fluidRectors
     */
    public function __construct(
        private array $fluidRectors
    ) {
    }

    public function supports(File $file): bool
    {
        $smartFileInfo = $file->getSmartFileInfo();

        return in_array($smartFileInfo->getExtension(), $this->getSupportedFileExtensions(), true);
    }

    /**
     * @param File[] $files
     */
    public function process(array $files): void
    {
        if ([] === $this->fluidRectors) {
            return;
        }

        foreach ($files as $file) {
            $this->processFile($file);
        }
    }

    public function getSupportedFileExtensions(): array
    {
        return ['html', 'xml', 'txt'];
    }

    private function processFile(File $file): void
    {
        foreach ($this->fluidRectors as $fluidRector) {
            $fluidRector->transform($file);
        }
    }
}
