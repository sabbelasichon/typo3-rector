<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FlexForms;

use DOMDocument;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\NonPhpFile\NonPhpFileChange;
use Ssch\TYPO3Rector\FlexForms\Transformer\FlexFormTransformer;
use Symplify\SmartFileSystem\SmartFileInfo;
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

    public function process(SmartFileInfo $smartFileInfo): ?NonPhpFileChange
    {
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

        return new NonPhpFileChange($smartFileInfo->getContents(), html_entity_decode($xml) . "\n");
    }

    public function supports(SmartFileInfo $smartFileInfo): bool
    {
        if ([] === $this->transformer) {
            return false;
        }

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
}
