<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Fluid;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Ssch\TYPO3Rector\Contract\FileProcessor\Fluid\Rector\FluidRectorInterface;

/**
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\Fluid\FluidProcessorTest
 */
final class FluidFileProcessor implements FileProcessorInterface
{
    /**
     * @param FluidRectorInterface[] $fluidRectors
     */
    public function __construct(
        private array $fluidRectors
    ) {
    }

    public function supports(File $file, Configuration $configuration): bool
    {
        $smartFileInfo = $file->getSmartFileInfo();

        return in_array($smartFileInfo->getExtension(), $this->getSupportedFileExtensions(), true);
    }

    public function process(File $file, Configuration $configuration): void
    {
        if ([] === $this->fluidRectors) {
            return;
        }

        foreach ($this->fluidRectors as $fluidRector) {
            $fluidRector->transform($file);
        }
    }

    /**
     * @return string[]
     */
    public function getSupportedFileExtensions(): array
    {
        return ['html', 'xml', 'txt'];
    }
}
