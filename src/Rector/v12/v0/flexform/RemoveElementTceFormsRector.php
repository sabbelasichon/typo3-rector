<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\flexform;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use Ssch\TYPO3Rector\Contract\FileProcessor\FlexForms\Rector\FlexFormRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-97126-TCEformsRemovedInFlexForm.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\flexform\RemoveElementTceFormsRector\RemoveElementTceFormsRectorTest
 */
final class RemoveElementTceFormsRector implements FlexFormRectorInterface
{
    public function transform(DOMDocument $domDocument): bool
    {
        $hasChanged = false;

        // Create a DOMXPath object to query the document
        $xpath = new DOMXPath($domDocument);

        // Find all elements with a <TCEforms> parent and move their children to the parent level
        $tceformsElements = $xpath->query('//TCEforms');
        if (! $tceformsElements instanceof DOMNodeList) {
            return false;
        }

        foreach ($tceformsElements as $tceformsElement) {
            $parent = $tceformsElement->parentNode;
            if (! $parent instanceof DOMElement) {
                return false;
            }

            // Move the children of <TCEforms> to the parent
            foreach ($tceformsElement->childNodes as $childNode) {
                if (! $childNode instanceof DOMElement) {
                    continue;
                }

                $parent->insertBefore($childNode->cloneNode(true), $tceformsElement);
            }

            // Remove the <TCEforms> element
            $parent->removeChild($tceformsElement);
            $hasChanged = true;
        }

        return $hasChanged;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove TCEForms key from all elements in data structure', [new CodeSample(
            <<<'CODE_SAMPLE'
<T3DataStructure>
    <ROOT>
        <TCEforms>
            <sheetTitle>aTitle</sheetTitle>
        </TCEforms>
        <type>array</type>
        <el>
            <aFlexField>
                <TCEforms>
                    <label>aFlexFieldLabel</label>
                    <config>
                        <type>input</type>
                    </config>
                </TCEforms>
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
                    <type>input</type>
                </config>
            </aFlexField>
        </el>
    </ROOT>
</T3DataStructure>
CODE_SAMPLE
        )]);
    }
}
