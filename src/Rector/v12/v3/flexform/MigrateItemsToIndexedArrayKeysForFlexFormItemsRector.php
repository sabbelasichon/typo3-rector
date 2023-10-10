<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v3\flexform;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use Ssch\TYPO3Rector\Contract\FileProcessor\FlexForms\Rector\FlexFormRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * This Rector Rule is sponsored by UDG Rhein-Main GmbH
 *
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.3/Deprecation-99739-IndexedArrayKeysForTCAItems.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v3\flexform\MigrateItemsToIndexedArrayKeysForFlexFormItemsRector\MigrateItemsToIndexedArrayKeysForFlexFormItemsRectorTest
 */
final class MigrateItemsToIndexedArrayKeysForFlexFormItemsRector implements FlexFormRectorInterface
{
    public function transform(DOMDocument $domDocument): bool
    {
        $hasChanged = false;

        $xpath = new DOMXPath($domDocument);

        /** @var DOMNodeList<DOMElement> $elements */
        $elements = $xpath->query('//config');

        if ($elements->count() === 0) {
            return false;
        }

        foreach ($elements as $element) {
            /** @var DOMElement $element */
            $type = $element->getElementsByTagName('type')
                ->item(0);

            if (! $type instanceof DOMElement) {
                continue;
            }

            if ($type->textContent !== 'select' && $type->textContent !== 'radio' && $type->textContent !== 'check') {
                continue;
            }

            $items = $element->getElementsByTagName('items')
                ->item(0);

            if (! $items instanceof DOMElement) {
                continue;
            }

            $array_filter = [];
            foreach ($items->childNodes as $item) {
                if ($item instanceof DOMElement && $item->nodeName === 'numIndex') {
                    $array_filter[] = $item;
                }
            }

            // These are the main numIndex items
            $numIndexes = $array_filter;

            if ($numIndexes === []) {
                continue;
            }

            foreach ($numIndexes as $item) {
                /** @var DOMElement $item */

                $numIndexes = $item->getElementsByTagName('numIndex');
                $label = $numIndexes->item(0);
                $value = $numIndexes->item(1);
                $icon = $numIndexes->item(2);
                $group = $numIndexes->item(3);
                $description = $numIndexes->item(4);
                if (! $label instanceof DOMElement) {
                    continue;
                }

                $hasChanged = true;
                $this->changeTagName($label, 'label');

                if ($type->textContent !== 'check') {
                    if (! $value instanceof DOMElement) {
                        continue;
                    }

                    $hasChanged = true;
                    $this->changeTagName($value, 'value');
                }

                if ($type->textContent === 'select') {
                    if (! $icon instanceof DOMElement) {
                        continue;
                    }

                    $hasChanged = true;
                    $this->changeTagName($icon, 'icon');

                    if (! $group instanceof DOMElement) {
                        continue;
                    }

                    $hasChanged = true;
                    $this->changeTagName($group, 'group');

                    if (! $description instanceof DOMElement) {
                        continue;
                    }

                    $hasChanged = true;
                    $this->changeTagName($description, 'description');
                }
            }
        }

        return $hasChanged;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Migrates indexed item array keys to associative for type select, radio and check in FlexForms. This Rector Rule is sponsored by UDG Rhein-Main GmbH',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
<select_single_1>
    <label>select_single_1 description</label>
    <description>field description</description>
    <config>
        <type>select</type>
        <renderType>selectSingle</renderType>
        <items>
            <numIndex index="0">
                <numIndex index="0">Label 1</numIndex>
                <numIndex index="1">value1</numIndex>
            </numIndex>
            <numIndex index="1">
                <numIndex index="0">Label 2</numIndex>
                <numIndex index="1">value2</numIndex>
            </numIndex>
        </items>
    </config>
</select_single_1>
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
<select_single_1>
    <label>select_single_1 description</label>
    <description>field description</description>
    <config>
        <type>select</type>
        <renderType>selectSingle</renderType>
        <items>
            <numIndex index="0">
                <label>Label 1</label>
                <value>value1</value>
            </numIndex>
            <numIndex index="1">
                <label>Label 2</label>
                <value>value2</value>
            </numIndex>
        </items>
    </config>
</select_single_1>
CODE_SAMPLE
                ),
            ]
        );
    }

    private function changeTagName(DOMElement $node, string $name): void
    {
        $childNodes = [];
        foreach ($node->childNodes as $child) {
            $childNodes[] = $child;
        }

        if (! $node->ownerDocument instanceof DOMDocument) {
            return;
        }

        $newNode = $node->ownerDocument->createElement($name);
        foreach ($childNodes as $child) {
            $child2 = $node->ownerDocument->importNode($child, false);
            $newNode->appendChild($child2);
        }

        if (! $node->parentNode instanceof DOMNode) {
            return;
        }

        $node->parentNode->replaceChild($newNode, $node);
    }
}
