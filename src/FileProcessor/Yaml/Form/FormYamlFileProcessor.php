<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Yaml\Form;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Ssch\TYPO3Rector\Contract\FileProcessor\Yaml\Form\FormYamlRectorInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\Yaml\Form\FormYamlProcessorTest
 */
final class FormYamlFileProcessor implements FileProcessorInterface
{
    /**
     * @var string[]
     */
    private const ALLOWED_FILE_EXTENSIONS = ['yaml'];

    /**
     * @param FormYamlRectorInterface[] $transformer
     */
    public function __construct(
        private CurrentFileProvider $currentFileProvider,
        private array $transformer
    ) {
    }

    public function process(File $file, Configuration $configuration): void
    {
        // Prevent unnecessary processing
        if ([] === $this->transformer) {
            return;
        }

        $this->currentFileProvider->setFile($file);

        $smartFileInfo = $file->getSmartFileInfo();
        $yaml = Yaml::parseFile($smartFileInfo->getRealPath());

        if (! is_array($yaml)) {
            return;
        }

        $newYaml = $yaml;

        foreach ($this->transformer as $transformer) {
            $newYaml = $transformer->refactor($newYaml);
        }

        // Nothing has changed. Early return here.
        if ($newYaml === $yaml) {
            return;
        }

        $newFileContent = Yaml::dump($newYaml, 99);
        $file->changeFileContent($newFileContent);
    }

    public function supports(File $file, Configuration $configuration): bool
    {
        $smartFileInfo = $file->getSmartFileInfo();

        return \str_ends_with($smartFileInfo->getFilename(), 'yaml');
    }

    /**
     * @return string[]
     */
    public function getSupportedFileExtensions(): array
    {
        return self::ALLOWED_FILE_EXTENSIONS;
    }
}
