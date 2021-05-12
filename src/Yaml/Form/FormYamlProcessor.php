<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Yaml\Form;

use Nette\Utils\Strings;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\Yaml\Form\FormYamlRectorInterface;
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
     * @var FormYamlRectorInterface[]
     */
    private $transformer = [];

    /**
     * @var CurrentFileProvider
     */
    private $currentFileProvider;

    /**
     * @param FormYamlRectorInterface[] $transformer
     */
    public function __construct(CurrentFileProvider $currentFileProvider, array $transformer)
    {
        $this->transformer = $transformer;
        $this->currentFileProvider = $currentFileProvider;
    }

    /**
     * @param File[] $files
     */
    public function process(array $files): void
    {
        // Prevent unnecessary processing
        if ([] === $this->transformer) {
            return;
        }

        foreach ($files as $file) {
            $this->processFile($file);
        }
    }

    public function supports(File $file): bool
    {
        $smartFileInfo = $file->getSmartFileInfo();

        return Strings::endsWith($smartFileInfo->getFilename(), 'form.yaml');
    }

    public function getSupportedFileExtensions(): array
    {
        return self::ALLOWED_FILE_EXTENSIONS;
    }

    private function processFile(File $file): void
    {
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
}
