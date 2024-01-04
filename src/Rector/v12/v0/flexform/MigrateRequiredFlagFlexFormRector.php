<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\flexform;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use Ssch\TYPO3Rector\Contract\FileProcessor\FlexForms\Rector\FlexFormRectorInterface;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Ssch\TYPO3Rector\Helper\FlexFormHelperTrait;
use Ssch\TYPO3Rector\Helper\StringUtility;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-97035-RequiredOptionInEvalKeyword.html
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97035-UtilizeRequiredDirectlyInTCAFieldConfiguration.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\flexform\MigrateRequiredFlagFlexFormRector\MigrateRequiredFlagFlexFormRectorTest
 */
final class MigrateRequiredFlagFlexFormRector implements FlexFormRectorInterface
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
        return new RuleDefinition('Migrate required flag', [new CodeSample(
            <<<'CODE_SAMPLE'
<T3DataStructure>
    <ROOT>
        <sheetTitle>aTitle</sheetTitle>
        <type>array</type>
        <el>
            <some_column>
                <title>foo</title>
                <config>
                    <eval>trim,required</eval>
                </config>
            </some_column>
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
            <some_column>
                <title>foo</title>
                <config>
                    <eval>trim</eval>
                    <required>1</required>
                </config>
            </some_column>
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

        if (! $this->hasKey($configElement, 'eval')) {
            return;
        }

        $evalDomElement = $this->extractDomElementByKey($configElement, 'eval');
        if (! $evalDomElement instanceof DOMElement) {
            return;
        }

        $evalListValue = $evalDomElement->nodeValue;
        if (! is_string($evalListValue)) {
            return;
        }

        if (! StringUtility::inList($evalListValue, 'required')) {
            return;
        }

        $evalList = ArrayUtility::trimExplode(',', $evalListValue, true);

        // Remove "required" from $evalList
        $evalList = array_filter($evalList, static fn (string $eval) => $eval !== 'required');

        if ($evalList !== []) {
            // Write back filtered 'eval'
            $evalDomElement->nodeValue = '';
            $evalDomElement->appendChild($domDocument->createTextNode(implode(',', $evalList)));
        } elseif ($evalDomElement->parentNode instanceof DOMElement) {
            // 'eval' is empty, remove whole configuration
            $evalDomElement->parentNode->removeChild($evalDomElement);
        }

        $requiredDomElement = $this->extractDomElementByKey($configElement, 'required');
        if (! $requiredDomElement instanceof DOMElement) {
            $configElement->appendChild($domDocument->createElement('required', '1'));
        }

        $this->domDocumentHasBeenChanged = true;
    }
}
