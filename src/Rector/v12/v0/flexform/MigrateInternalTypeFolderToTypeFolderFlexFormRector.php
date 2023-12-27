<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\flexform;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use Ssch\TYPO3Rector\Contract\FileProcessor\FlexForms\Rector\FlexFormRectorInterface;
use Ssch\TYPO3Rector\Helper\FlexFormHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-96983-TCATypeFolder.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\flexform\MigrateInternalTypeFolderToTypeFolderFlexFormRector\MigrateInternalTypeFolderToTypeFolderFlexFormRectorTest
 */
final class MigrateInternalTypeFolderToTypeFolderFlexFormRector implements FlexFormRectorInterface
{
    use FlexFormHelperTrait;

    private bool $domDocumentHasBeenChanged = false;

    public function transform(DOMDocument $domDocument): bool
    {
        $xpath = new DOMXPath($domDocument);

        /** @var DOMNodeList<DOMElement> $elements */
        $elements = $xpath->query('//config');

        if ($elements->count() === 0) {
            return false;
        }

        foreach ($elements as $element) {
            $this->refactorColumn($domDocument, $element);
        }

        return $this->domDocumentHasBeenChanged;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrates TCA internal_type into new new TCA type folder', [new CodeSample(
            <<<'CODE_SAMPLE'
<T3DataStructure>
    <ROOT>
        <sheetTitle>aTitle</sheetTitle>
        <type>array</type>
        <el>
            <aFlexField>
                <label>aFlexFieldLabel</label>
                <config>
                    <type>group</type>
                    <internal_type>folder</internal_type>
                </config>
            </aFlexField>
        </el>
    </ROOT>
</T3DataStructure>
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
<T3DataStructure>
    <ROOT>
        <sheetTitle>aTitle</sheetTitle>
        <type>array</type>
        <el>
            <aFlexField>
                <label>aFlexFieldLabel</label>
                <config>
                    <type>folder</type>
                </config>
            </aFlexField>
        </el>
    </ROOT>
</T3DataStructure>
CODE_SAMPLE
        )]);
    }

    private function refactorColumn(DOMDocument $domDocument, ?DOMElement $configElement): void
    {
        if (! $configElement instanceof DOMElement) {
            return;
        }

        if (! $this->isConfigType($configElement, 'group') || ! $this->hasKey($configElement, 'internal_type')) {
            return;
        }

        $internalTypeDomElement = $this->extractDomElementByKey($configElement, 'internal_type');

        if (! $internalTypeDomElement instanceof DOMElement) {
            return;
        }

        // Unset
        if ($internalTypeDomElement->parentNode instanceof DOMElement) {
            $internalTypeDomElement->parentNode->removeChild($internalTypeDomElement);
        }

        $internalTypeValue = $internalTypeDomElement->nodeValue;

        if ($internalTypeValue === 'folder') {
            $toChangeItem = $this->extractDomElementByKey($configElement, 'type');
            if ($toChangeItem instanceof DOMElement) {
                $toChangeItem->nodeValue = '';
                $toChangeItem->appendChild($domDocument->createTextNode('folder'));
            }
        }

        $this->domDocumentHasBeenChanged = true;
    }
}
