<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use DOMDocument;
use DOMElement;
use DOMNode;

trait FlexFormHelperTrait
{
    protected function extractDomElementByKey(?DOMElement $element, string $key): ?DOMElement
    {
        if (! $element instanceof DOMElement) {
            return null;
        }

        foreach ($element->childNodes as $childNode) {
            if (! $childNode instanceof DOMElement) {
                continue;
            }

            $itemKey = (string) $childNode->nodeName;
            if ($key === $itemKey) {
                return $childNode;
            }
        }

        return null;
    }

    protected function hasKey(?DOMElement $columnItemConfigurationArray, string $configKey): bool
    {
        if (! $columnItemConfigurationArray instanceof \DOMElement) {
            return false;
        }

        foreach ($columnItemConfigurationArray->childNodes as $item) {
            if (! $item instanceof DOMElement) {
                continue;
            }

            if ($this->isValue($item->nodeName, $configKey)) {
                return true;
            }
        }

        return false;
    }

    private function isConfigType(?DOMElement $columnItemConfigurationArray, string $type): bool
    {
        if (! $columnItemConfigurationArray instanceof \DOMElement) {
            return false;
        }

        return $this->hasKeyValuePair($columnItemConfigurationArray, 'type', $type);
    }

    private function hasRenderType(DOMElement $columnItemConfigurationArray): bool
    {
        $renderTypeItem = $this->extractDomElementByKey($columnItemConfigurationArray, 'renderType');
        return $renderTypeItem !== null;
    }

    private function hasKeyValuePair(DOMElement $configValueArray, string $configKey, string $expectedValue): bool
    {
        foreach ($configValueArray->childNodes as $configItemValue) {
            if (! $configItemValue instanceof DOMElement) {
                continue;
            }

            if ($this->isValue($configItemValue->nodeName, $configKey)
                && $this->isValue($configItemValue->textContent, $expectedValue)
            ) {
                return true;
            }
        }

        return false;
    }

    private function isValue(string $element, string $value): bool
    {
        return $element === $value;
    }

    /**
     * @see https://stackoverflow.com/a/21885789
     */
    private function changeTagName(DOMDocument $domDocument, DOMElement $node, string $elementName): void
    {
        $newNode = $domDocument->createElement($elementName);
        foreach ($node->childNodes as $child) {
            $newNode->appendChild($domDocument->importNode($child, false));
        }

        // We don't need to copy the attributes because TCA doesn't have attributes

        if (! $node->parentNode instanceof DOMNode) {
            return;
        }

        $node->parentNode->replaceChild($newNode, $node);
    }
}
