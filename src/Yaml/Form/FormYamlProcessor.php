<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Yaml\Form;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Yaml\Form\Transformer\FormYamlTransformer;
use Symfony\Component\Yaml\Yaml;

/**
 * @see \Ssch\TYPO3Rector\Tests\Yaml\Form\FormYamlProcessorTest
 */
final class FormYamlProcessor implements FileProcessorInterface
{
    /**
     * @var string[]
     */
    private const ALLOWED_FILE_EXTENSIONS = ['yaml'];

    /**
     * @var FormYamlTransformer[]
     */
    private $transformer = [];

    /**
     * @param FormYamlTransformer[] $transformer
     */
    public function __construct(array $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @param File[] $files
     */
    public function process(array $files): void
    {
        foreach ($files as $file) {
            $this->processFile($file);
        }
    }

    public function supports(File $file): bool
    {
        if ([] === $this->transformer) {
            return false;
        }
        $smartFileInfo = $file->getSmartFileInfo();

        return in_array($smartFileInfo->getExtension(), self::ALLOWED_FILE_EXTENSIONS, true);
    }

    public function getSupportedFileExtensions(): array
    {
        return self::ALLOWED_FILE_EXTENSIONS;
    }

    private function processFile(File $file): void
    {
        $smartFileInfo = $file->getSmartFileInfo();

        $yaml = Yaml::parseFile($smartFileInfo->getRealPath());

        if (! is_array($yaml)) {
            return;
        }

        foreach ($this->transformer as $transformer) {
            $yaml = $transformer->transform($yaml);
        }

        $changedContent = Yaml::dump($yaml, 99, 2);

        $file->changeFileContent($changedContent);
    }
}
