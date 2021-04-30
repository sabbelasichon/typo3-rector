<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Yaml\Form;

use Nette\Utils\Strings;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\EditorConfig\EditorConfigParser;
use Ssch\TYPO3Rector\ValueObject\EditorConfigConfiguration;
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
     * @var CurrentFileProvider
     */
    private $currentFileProvider;

    /**
     * @var EditorConfigParser
     */
    private $editorConfigParser;

    /**
     * @param FormYamlTransformer[] $transformer
     */
    public function __construct(
        CurrentFileProvider $currentFileProvider,
        EditorConfigParser $editorConfigParser,
        array $transformer
    ) {
        $this->transformer = $transformer;
        $this->currentFileProvider = $currentFileProvider;
        $this->editorConfigParser = $editorConfigParser;
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

        foreach ($this->transformer as $transformer) {
            $yaml = $transformer->transform($yaml);
        }

        $defaultEditorConfiguration = new EditorConfigConfiguration(
            EditorConfigConfiguration::SPACE,
            2,
            EditorConfigConfiguration::LINE_FEED
        );
        $editorConfiguration = $this->editorConfigParser->extractConfigurationForFile(
            $smartFileInfo,
            $defaultEditorConfiguration
        );

        $changedContent = Yaml::dump($yaml, 99, $editorConfiguration->getIndentSize());

        $file->changeFileContent($changedContent);
    }
}
