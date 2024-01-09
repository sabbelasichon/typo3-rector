<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\FlexForm;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;

abstract class AbstractFlexFormRector
{
    protected DOMDocument $domDocument;

    protected bool $domDocumentHasBeenChanged = false;

    public function transform(DOMDocument $domDocument): bool
    {
        $this->domDocument = $domDocument;

        $xpath = new DOMXPath($domDocument);

        /** @var DOMNodeList<DOMElement> $elements */
        $elements = $xpath->query('//config');

        if ($elements->count() === 0) {
            return false;
        }

        foreach ($elements as $element) {
            $this->refactorColumn($element);
        }

        return $this->domDocumentHasBeenChanged;
    }

    abstract protected function refactorColumn(?DOMElement $configElement): void;
}
