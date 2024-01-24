<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\FlexForms;

use DOMDocument;
use Exception;
use Rector\ChangesReporting\ValueObjectFactory\FileDiffFactory;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\Parallel\ValueObject\Bridge;
use Ssch\TYPO3Rector\Contract\FileProcessor\FlexForms\Rector\FlexFormRectorInterface;
use Ssch\TYPO3Rector\Filesystem\FileInfoFactory;
use UnexpectedValueException;

final class FlexFormsProcessor implements FileProcessorInterface
{
    /**
     * @var FlexFormRectorInterface[]
     * @readonly
     */
    private iterable $flexFormRectors = [];

    /**
     * @readonly
     */
    private FileDiffFactory $fileDiffFactory;

    /**
     * @readonly
     */
    private FileInfoFactory $fileInfoFactory;

    /**
     * @param FlexFormRectorInterface[] $flexFormRectors
     */
    public function __construct(iterable $flexFormRectors, FileDiffFactory $fileDiffFactory, FileInfoFactory $fileInfoFactory)
    {
        $this->flexFormRectors = $flexFormRectors;
        $this->fileDiffFactory = $fileDiffFactory;
        $this->fileInfoFactory = $fileInfoFactory;
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

        $oldFileContents = $file->getFileContent();

        $domDocument = new DOMDocument();
        $domDocument->preserveWhiteSpace = true;
        $domDocument->formatOutput = true;
        $domDocument->loadXML($oldFileContents);

        $hasChanged = false;
        foreach ($this->flexFormRectors as $flexFormRector) {
            $hasChanged = $flexFormRector->transform($domDocument);
        }

        if (! $hasChanged) {
            return $systemErrorsAndFileDiffs;
        }

        $xml = $domDocument->saveXML($domDocument->documentElement, LIBXML_NOEMPTYTAG);
        if ($xml === false) {
            throw new UnexpectedValueException('Could not convert to xml');
        }

        // Remove empty lines
        /** @see https://stackoverflow.com/a/709684 */
        $xml = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $xml);

        // add end of line
        $xml .= PHP_EOL;

        // nothing has changed
        if ($oldFileContents === $xml) {
            return $systemErrorsAndFileDiffs;
        }

        $newFileContent = html_entity_decode($xml);
        $file->changeFileContent($newFileContent);

        $fileDiff = $this->fileDiffFactory->createFileDiff($file, $oldFileContents, $newFileContent);
        $systemErrorsAndFileDiffs[Bridge::FILE_DIFFS][] = $fileDiff;

        return $systemErrorsAndFileDiffs;
    }

    public function supports(File $file, Configuration $configuration): bool
    {
        // avoid empty run
        if ($this->flexFormRectors === []) {
            return false;
        }

        $smartFileInfo = $this->fileInfoFactory->createFileInfoFromPath($file->getFilePath());

        if (! in_array($smartFileInfo->getExtension(), $this->getSupportedFileExtensions(), true)) {
            return false;
        }

        $fileContent = $file->getFileContent();

        try {
            $xml = @simplexml_load_string($fileContent);
        } catch (Exception $exception) {
            return false;
        }

        if ($xml === false) {
            return false;
        }

        return $xml->getName() === 'T3DataStructure';
    }

    /**
     * @return string[]
     */
    public function getSupportedFileExtensions(): array
    {
        return ['xml'];
    }
}
