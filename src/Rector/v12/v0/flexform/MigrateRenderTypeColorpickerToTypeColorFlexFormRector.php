<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\flexform;

use DOMElement;
use Ssch\TYPO3Rector\Contract\FileProcessor\FlexForms\Rector\FlexFormRectorInterface;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Ssch\TYPO3Rector\Helper\FlexFormHelperTrait;
use Ssch\TYPO3Rector\Rector\FlexForm\AbstractFlexFormRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97271-NewTCATypeColor.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\flexform\MigrateRenderTypeColorpickerToTypeColorFlexFormRector\MigrateRenderTypeColorpickerToTypeColorFlexFormRectorTest
 */
final class MigrateRenderTypeColorpickerToTypeColorFlexFormRector extends AbstractFlexFormRector implements FlexFormRectorInterface
{
    use FlexFormHelperTrait;

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate renderType colorpicker to type color', [new CodeSample(
            <<<'CODE_SAMPLE'
<T3DataStructure>
    <ROOT>
        <sheetTitle>aTitle</sheetTitle>
        <type>array</type>
        <el>
            <a_color_field>
                <label>Color field</label>
                <config>
                    <type>input</type>
                    <renderType>colorpicker</renderType>
                    <required>1</required>
                    <size>20</size>
                    <max>1234</max>
                    <eval>trim,null</eval>
                    <valuePicker>
                        <items type="array">
                            <numIndex index="0" type="array">
                                <numIndex index="0">typo3 orange</numIndex>
                                <numIndex index="1">#FF8700</numIndex>
                            </numIndex>
                        </items>
                    </valuePicker>
                </config>
            </a_color_field>
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
            <a_color_field>
                <label>Color field</label>
                <config>
                    <type>color</type>
                    <required>1</required>
                    <size>20</size>
                    <valuePicker>
                        <items type="array">
                            <numIndex index="0" type="array">
                                <numIndex index="0">typo3 orange</numIndex>
                                <numIndex index="1">#FF8700</numIndex>
                            </numIndex>
                        </items>
                    </valuePicker>
                </config>
            </a_color_field>
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

        if (! $this->isConfigType($configElement, 'input')) {
            return;
        }

        if (! $this->configIsOfRenderType($configElement, 'colorpicker')) {
            return;
        }

        // Set the TCA type to "color"
        $this->changeTcaType($this->domDocument, $configElement, 'color');

        // Remove 'max' and 'renderType' config
        $this->removeChildElementFromDomElementByKey($configElement, 'max');
        $this->removeChildElementFromDomElementByKey($configElement, 'renderType');

        $evalDomElement = $this->extractDomElementByKey($configElement, 'eval');
        if (! $evalDomElement instanceof DOMElement) {
            return;
        }

        $evalListValue = $evalDomElement->nodeValue;
        if (! is_string($evalListValue)) {
            return;
        }

        $evalList = ArrayUtility::trimExplode(',', $evalListValue, true);

        if (in_array('null', $evalList, true)) {
            // Set "eval" to "null", since it's currently defined and the only allowed "eval" for type=color
            $evalDomElement->nodeValue = '';
            $evalDomElement->appendChild($this->domDocument->createTextNode('null'));
        } elseif ($evalDomElement->parentNode instanceof DOMElement) {
            // 'eval' is empty, remove whole configuration
            $evalDomElement->parentNode->removeChild($evalDomElement);
        }

        $this->domDocumentHasBeenChanged = true;
    }
}
