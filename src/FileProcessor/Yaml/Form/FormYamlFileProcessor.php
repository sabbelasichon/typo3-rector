<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Yaml\Form;

use Nette\Utils\Strings;
use Rector\ChangesReporting\ValueObjectFactory\FileDiffFactory;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\Parallel\ValueObject\Bridge;
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
     * @var string
     * @see https://regex101.com/r/rUHuF8/1
     */
    private const FIRST_INDENT_REGEX = '#^(?<first_indent>\s+)[\w\-]#m';

    /**
     * @param FormYamlRectorInterface[] $transformer
     */
    public function __construct(
        private CurrentFileProvider $currentFileProvider,
        private FileDiffFactory $fileDiffFactory,
        private array $transformer
    ) {
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

        $smartFileInfo = $file->getSmartFileInfo();
        $oldYamlContent = $smartFileInfo->getContents();
        $yaml = Yaml::parse($oldYamlContent);

        if (! is_array($yaml)) {
            return $systemErrorsAndFileDiffs;
        }

        $newYaml = $yaml;

        foreach ($this->transformer as $transformer) {
            $newYaml = $transformer->refactor($newYaml);
        }

        // Nothing has changed. Early return here.
        if ($newYaml === $yaml) {
            return $systemErrorsAndFileDiffs;
        }

        $spaceCount = $this->resolveYamlIndentSpaceCount($oldYamlContent);

        $newFileContent = Yaml::dump($newYaml, 99, $spaceCount);
        $file->changeFileContent($newFileContent);

        $fileDiff = $this->fileDiffFactory->createFileDiff($file, $oldYamlContent, $newFileContent);
        $systemErrorsAndFileDiffs[Bridge::FILE_DIFFS][] = $fileDiff;

        return $systemErrorsAndFileDiffs;
    }

    public function supports(File $file, Configuration $configuration): bool
    {
        // Prevent unnecessary processing
        if ([] === $this->transformer) {
            return false;
        }

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

    private function resolveYamlIndentSpaceCount(string $oldYamlContent): int
    {
        $firstSpaceMatch = Strings::match($oldYamlContent, self::FIRST_INDENT_REGEX);
        if (! isset($firstSpaceMatch['first_indent'])) {
            // default to 4
            return 4;
        }

        $firstIndent = $firstSpaceMatch['first_indent'];
        return substr_count($firstIndent, ' ');
    }
}
