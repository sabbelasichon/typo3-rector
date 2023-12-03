<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Yaml;

use Rector\ChangesReporting\ValueObjectFactory\FileDiffFactory;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\Parallel\ValueObject\Bridge;
use Ssch\TYPO3Rector\Contract\FileProcessor\Yaml\YamlRectorInterface;
use Ssch\TYPO3Rector\Helper\SymfonyYamlParser;
use Ssch\TYPO3Rector\ValueObject\Indent;
use Symplify\SmartFileSystem\SmartFileInfo;

final class YamlFileProcessor implements FileProcessorInterface
{
    /**
     * @var string[]
     */
    private const ALLOWED_FILE_EXTENSIONS = ['yaml'];

    /**
     * @readonly
     */
    private CurrentFileProvider $currentFileProvider;

    /**
     * @readonly
     */
    private FileDiffFactory $fileDiffFactory;

    /**
     * @var YamlRectorInterface[]
     * @readonly
     */
    private array $yamlRectors = [];

    /**
     * @readonly
     */
    private SymfonyYamlParser $symfonyYamlParser;

    /**
     * @param YamlRectorInterface[] $yamlRectors
     */
    public function __construct(
        CurrentFileProvider $currentFileProvider,
        FileDiffFactory $fileDiffFactory,
        SymfonyYamlParser $symfonyYamlParser,
        array $yamlRectors
    ) {
        $this->currentFileProvider = $currentFileProvider;
        $this->fileDiffFactory = $fileDiffFactory;
        $this->yamlRectors = $yamlRectors;
        $this->symfonyYamlParser = $symfonyYamlParser;
    }

    /**
     * @return array{system_errors: SystemError[], file_diffs: FileDiff[]}
     */
    public function process(File $file, Configuration $configuration): array
    {
        $systemErrorsAndFileDiffs = [
            Bridge::SYSTEM_ERRORS => [],
            Bridge::FILE_DIFFS => [],
        ];

        $this->currentFileProvider->setFile($file);

        $indent = Indent::fromFile($file);

        $oldYamlContent = $file->getFileContent();
        $yaml = $this->symfonyYamlParser->parse($file->getFilePath(), $oldYamlContent);

        if (! is_array($yaml)) {
            return $systemErrorsAndFileDiffs;
        }

        $newYaml = $yaml;

        foreach ($this->yamlRectors as $yamlRector) {
            $newYaml = $yamlRector->refactor($newYaml);
        }

        // Nothing has changed. Early return here.
        if ($newYaml === $yaml) {
            return $systemErrorsAndFileDiffs;
        }

        $newFileContent = $this->symfonyYamlParser->dump($newYaml, $indent->length());
        $file->changeFileContent($newFileContent);

        $fileDiff = $this->fileDiffFactory->createFileDiff($file, $oldYamlContent, $newFileContent);
        $systemErrorsAndFileDiffs[Bridge::FILE_DIFFS][] = $fileDiff;

        return $systemErrorsAndFileDiffs;
    }

    public function supports(File $file, Configuration $configuration): bool
    {
        // Prevent unnecessary processing
        if ($this->yamlRectors === []) {
            return false;
        }

        $smartFileInfo = new SmartFileInfo($file->getFilePath());

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
