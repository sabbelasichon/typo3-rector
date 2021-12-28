<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\FlexForms;

use DOMDocument;
use Exception;
use Rector\ChangesReporting\ValueObjectFactory\FileDiffFactory as FileDiffFactoryAlias;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Parallel\ValueObject\Bridge;
use Ssch\TYPO3Rector\Contract\FileProcessor\FlexForms\Rector\FlexFormRectorInterface;
use UnexpectedValueException;

/**
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\FlexForms\FlexFormsProcessorTest
 */
final class FlexFormsProcessor implements FileProcessorInterface
{
    /**
     * @param FlexFormRectorInterface[] $flexFormRectors
     */
    public function __construct(
        private array $flexFormRectors,
        private FileDiffFactoryAlias $fileDiffFactory,
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

        if ([] === $this->flexFormRectors) {
            return $systemErrorsAndFileDiffs;
        }

        $domDocument = new DOMDocument();
        $domDocument->formatOutput = true;
        $domDocument->loadXML($file->getFileContent());

        $hasChanged = false;
        foreach ($this->flexFormRectors as $flexFormRector) {
            $hasChanged = $flexFormRector->transform($domDocument);
        }

        if (! $hasChanged) {
            return $systemErrorsAndFileDiffs;
        }

        $xml = $domDocument->saveXML($domDocument->documentElement, LIBXML_NOEMPTYTAG);
        if (false === $xml) {
            throw new UnexpectedValueException('Could not convert to xml');
        }

        if ($xml === $file->getFileContent()) {
            return $systemErrorsAndFileDiffs;
        }

        $newFileContent = html_entity_decode($xml);
        $file->changeFileContent($newFileContent);

        $fileDiff = $this->fileDiffFactory->createFileDiff($file, $xml, $newFileContent);
        $systemErrorsAndFileDiffs[Bridge::FILE_DIFFS][] = $fileDiff;

        return $systemErrorsAndFileDiffs;
    }

    public function supports(File $file, Configuration $configuration): bool
    {
        $smartFileInfo = $file->getSmartFileInfo();

        if (! in_array($smartFileInfo->getExtension(), $this->getSupportedFileExtensions(), true)) {
            return false;
        }

        $fileContent = $file->getFileContent();

        try {
            $xml = @simplexml_load_string($fileContent);
        } catch (Exception) {
            return false;
        }

        if (false === $xml) {
            return false;
        }

        return 'T3DataStructure' === $xml->getName();
    }

    public function getSupportedFileExtensions(): array
    {
        return ['xml'];
    }
}
