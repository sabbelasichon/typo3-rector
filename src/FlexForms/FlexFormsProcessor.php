<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FlexForms;

use DOMDocument;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\FlexForms\Transformer\FlexFormTransformer;
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
     * @param FlexFormTransformer[] $transformer
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

        if ('xml' !== $smartFileInfo->getExtension()) {
            return false;
        }

        $xml = simplexml_load_file($smartFileInfo->getRealPath());

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

        $domDocument->load($smartFileInfo->getRealPath());

        foreach ($this->transformer as $transformer) {
            $transformer->transform($domDocument);
        }

        $xml = $domDocument->saveXML($domDocument->documentElement, LIBXML_NOEMPTYTAG);

        if (false === $xml) {
            throw new UnexpectedValueException('Could not convert to xml');
        }

        if ($xml === $file->getFileContent()) {
            return;
        }

        $changedContent = html_entity_decode($xml) . "\n";

        $file->changeFileContent($changedContent);
    }
}
