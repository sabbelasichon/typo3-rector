<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\flexform;

use DOMElement;
use Ssch\TYPO3Rector\Contract\FileProcessor\FlexForms\Rector\FlexFormRectorInterface;
use Ssch\TYPO3Rector\Helper\FlexFormHelperTrait;
use Ssch\TYPO3Rector\Rector\FlexForm\AbstractFlexFormRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-96983-TCATypeFolder.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\flexform\MigrateInternalTypeFolderToTypeFolderFlexFormRector\MigrateInternalTypeFolderToTypeFolderFlexFormRectorTest
 */
final class MigrateInternalTypeFolderToTypeFolderFlexFormRector extends AbstractFlexFormRector implements FlexFormRectorInterface
{
    use FlexFormHelperTrait;

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

    protected function refactorColumn(?DOMElement $configElement): void
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

        if ($internalTypeDomElement->nodeValue === 'folder') {
            $this->changeTcaType($this->domDocument, $configElement, 'folder');
        }

        $this->domDocumentHasBeenChanged = true;
    }
}
