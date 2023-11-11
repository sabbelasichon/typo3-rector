<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v3\flexform;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use Ssch\TYPO3Rector\Contract\FileProcessor\FlexForms\Rector\FlexFormRectorInterface;
use Ssch\TYPO3Rector\Helper\FlexFormHelperTrait;
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
    use FlexFormHelperTrait;

    private DOMDocument $domDocument;

    private bool $domDocumentHasBeenChanged = false;


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


    private function refactorColumn(?DOMElement $configElement): void
    {
        if (! $configElement instanceof DOMElement) {
            return;
        }

        if (! $this->isConfigType($configElement, 'select')
            && ! $this->isConfigType($configElement, 'radio')
            && ! $this->isConfigType($configElement, 'check')
        ) {
            return;
        }

        $items = $configElement->getElementsByTagName('items')
            ->item(0);

        if (! $items instanceof DOMElement) {
            return;
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
            return;
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

            $this->changeTagName($this->domDocument, $label, 'label');
            $this->domDocumentHasBeenChanged = true;

            if (! $this->isConfigType($configElement, 'check')) {
                if (! $value instanceof DOMElement) {
                    continue;
                }

                $this->changeTagName($this->domDocument, $value, 'value');
                $this->domDocumentHasBeenChanged = true;
            }

            if ($this->isConfigType($configElement, 'select')) {
                if (! $icon instanceof DOMElement) {
                    continue;
                }

                $this->changeTagName($this->domDocument, $icon, 'icon');
                $this->domDocumentHasBeenChanged = true;

                if (! $group instanceof DOMElement) {
                    continue;
                }

                $this->changeTagName($this->domDocument, $group, 'group');
                $this->domDocumentHasBeenChanged = true;

                if (! $description instanceof DOMElement) {
                    continue;
                }

                $this->changeTagName($this->domDocument, $description, 'description');
                $this->domDocumentHasBeenChanged = true;
            }
        }
    }
}
