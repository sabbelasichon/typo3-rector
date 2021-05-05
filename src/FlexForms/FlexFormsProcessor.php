<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FlexForms;

use DOMDocument;
use Exception;
use PrettyXml\Formatter;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\EditorConfig\EditorConfigParser;
use Ssch\TYPO3Rector\FlexForms\Transformer\FlexFormTransformer;
use Ssch\TYPO3Rector\ValueObject\EditorConfigConfiguration;
use UnexpectedValueException;

/**
 * @see \Ssch\TYPO3Rector\Tests\FlexForms\FlexFormsProcessorTest
 */
final class FlexFormsProcessor implements FileProcessorInterface
{
    /**
     * @var FlexFormTransformer[]
     */
    private $transformer = [];

    /**
     * @var EditorConfigParser
     */
    private $editorConfigParser;

    /**
     * @var Formatter
     */
    private $xmlFormatter;

    /**
     * @param FlexFormTransformer[] $transformer
     */
    public function __construct(array $transformer, EditorConfigParser $editorConfigParser, Formatter $xmlFormatter)
    {
        $this->transformer = $transformer;
        $this->editorConfigParser = $editorConfigParser;
        $this->xmlFormatter = $xmlFormatter;
    }

    /**
     * @param File[] $files
     */
    public function process(array $files): void
    {
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

        if (! in_array($smartFileInfo->getExtension(), $this->getSupportedFileExtensions(), true)) {
            return false;
        }

        $fileContent = $file->getFileContent();

        try {
            $xml = @simplexml_load_string($fileContent);
        } catch (Exception $exception) {
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

    private function processFile(File $file): void
    {
        $smartFileInfo = $file->getSmartFileInfo();

        $domDocument = new DOMDocument();

        $domDocument->formatOutput = true;

        $domDocument->loadXML($file->getFileContent());

        $hasChanged = false;
        foreach ($this->transformer as $transformer) {
            $hasChanged = $transformer->transform($domDocument);
        }

        if (! $hasChanged) {
            return;
        }

        $xml = $domDocument->saveXML($domDocument->documentElement, LIBXML_NOEMPTYTAG);

        if (false === $xml) {
            throw new UnexpectedValueException('Could not convert to xml');
        }

        if ($xml === $file->getFileContent()) {
            return;
        }

        $defaultEditorConfiguration = new EditorConfigConfiguration(
            EditorConfigConfiguration::TAB,
            1,
            EditorConfigConfiguration::LINE_FEED
        );
        $editorConfiguration = $this->editorConfigParser->extractConfigurationForFile(
            $smartFileInfo,
            $defaultEditorConfiguration
        );
        $this->xmlFormatter->setIndentCharacter($editorConfiguration->getIndentStyleCharacter());
        $this->xmlFormatter->setIndentSize($editorConfiguration->getIndentSize());

        $changedContent = html_entity_decode($this->xmlFormatter->format($xml)) . $editorConfiguration->getEndOfLine();

        $file->changeFileContent($changedContent);
    }
}
