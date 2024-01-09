<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\FlexForms\Rector\v7\v6;

use DOMElement;
use Ssch\TYPO3Rector\Contract\FileProcessor\FlexForms\Rector\FlexFormRectorInterface;
use Ssch\TYPO3Rector\Helper\FlexFormHelperTrait;
use Ssch\TYPO3Rector\Rector\FlexForm\AbstractFlexFormRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/7.6/Deprecation-69822-DeprecateSelectFieldTca.html
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\FlexForms\Rector\v7\v6\RenderTypeFlexFormRector\RenderTypeFlexFormRectorTest
 */
final class RenderTypeFlexFormRector extends AbstractFlexFormRector implements FlexFormRectorInterface
{
    use FlexFormHelperTrait;

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add renderType node in FlexForm', [
            new CodeSample(
                <<<'CODE_SAMPLE'
<T3DataStructure>
    <ROOT>
        <sheetTitle>aTitle</sheetTitle>
        <type>array</type>
        <el>
            <a_select_field>
                <label>Select field</label>
                <config>
                    <type>select</type>
                    <items>
                        <numIndex index="0" type="array">
                            <numIndex index="0">Label</numIndex>
                        </numIndex>
                    </items>
                </config>
            </a_select_field>
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
            <a_select_field>
                <label>Select field</label>
                <config>
                    <type>select</type>
                    <renderType>selectSingle</renderType>
                    <items>
                        <numIndex index="0" type="array">
                            <numIndex index="0">Label</numIndex>
                        </numIndex>
                    </items>
                </config>
            </a_select_field>
        </el>
    </ROOT>
</T3DataStructure>
CODE_SAMPLE
            ),
        ]);
    }

    protected function refactorColumn(?DOMElement $configElement): void
    {
        if (! $configElement instanceof DOMElement) {
            return;
        }

        if (! $this->isConfigType($configElement, 'select')) {
            return;
        }

        // Do not handle field where the render type is set.
        if ($this->hasRenderType($configElement)) {
            return;
        }

        $renderModeDomElement = $this->extractDomElementByKey($configElement, 'renderMode');
        if ($renderModeDomElement instanceof DOMElement) {
            $renderMode = $renderModeDomElement->nodeValue;
            switch ($renderMode) {
                case 'tree':
                    $renderTypeName = 'selectTree';
                    break;
                case 'singlebox':
                    $renderTypeName = 'selectSingleBox';
                    break;
                case 'checkbox':
                    $renderTypeName = 'selectCheckBox';
                    break;
                default:
                    $renderTypeName = null;
            }

            if ($renderTypeName !== null) {
                $configElement->appendChild($this->domDocument->createElement('renderType', $renderTypeName));

                $this->domDocumentHasBeenChanged = true;
            }

            return;
        }

        $maxItemsDomElement = $this->extractDomElementByKey($configElement, 'maxitems');
        if ($maxItemsDomElement instanceof DOMElement) {
            $maxItems = (int) $maxItemsDomElement->nodeValue;

            $renderTypeName = $maxItems <= 1 ? 'selectSingle' : 'selectMultipleSideBySide';
        } else {
            $renderTypeName = 'selectSingle';
        }

        $configElement->appendChild($this->domDocument->createElement('renderType', $renderTypeName));

        $this->domDocumentHasBeenChanged = true;
    }
}
