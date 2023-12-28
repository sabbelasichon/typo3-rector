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
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97193-NewTCATypeNumber.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\flexform\MigrateEvalIntAndDouble2ToTypeNumberFlexFormRector\MigrateEvalIntAndDouble2ToTypeNumberFlexFormRectorTest
 */
final class MigrateEvalIntAndDouble2ToTypeNumberFlexFormRector implements FlexFormRectorInterface
{
    use FlexFormHelperTrait;

    /**
     * @var string
     */
    private const INT = 'int';

    /**
     * @var string
     */
    private const DOUBLE2 = 'double2';

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
        return new RuleDefinition('Migrate eval int and double2 to type number', [new CodeSample(
            <<<'CODE_SAMPLE'
<int_field>
    <label>int field</label>
    <config>
        <type>input</type>
        <eval>int</eval>
    </config>
</int_field>
<double2_field>
    <label>double2 field</label>
    <config>
        <type>input</type>
        <eval>double2</eval>
    </config>
</double2_field>
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
<int_field>
    <label>int field</label>
    <config>
        <type>number</type>
    </config>
</int_field>
<double2_field>
    <label>double2 field</label>
    <config>
        <type>number</type>
        <format>decimal</format>
    </config>
</double2_field>
CODE_SAMPLE
        )]);
    }

    private function refactorColumn(DOMDocument $domDocument, ?DOMElement $configElement): void
    {
        if (! $configElement instanceof DOMElement) {
            return;
        }

        if (! $this->isConfigType($configElement, 'input') || $this->hasRenderType($configElement)) {
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

        if (! StringUtility::inList($evalListValue, self::INT)
            && ! StringUtility::inList($evalListValue, self::DOUBLE2)
        ) {
            return;
        }

        $evalList = ArrayUtility::trimExplode(',', $evalListValue, true);

        // Remove "int" from $evalList
        $evalList = array_filter(
            $evalList,
            static fn (string $eval) => $eval !== self::INT && $eval !== self::DOUBLE2
        );

        if ($evalList !== []) {
            // Write back filtered 'eval'
            $evalDomElement->nodeValue = '';
            $evalDomElement->appendChild($domDocument->createTextNode(implode(',', $evalList)));
        } elseif ($evalDomElement->parentNode instanceof DOMElement) {
            // 'eval' is empty, remove whole configuration
            $evalDomElement->parentNode->removeChild($evalDomElement);
        }

        $this->changeTcaType($domDocument, $configElement, 'number');

        if (StringUtility::inList($evalListValue, self::DOUBLE2)) {
            $configElement->appendChild($domDocument->createElement('format', 'decimal'));
        }

        $this->domDocumentHasBeenChanged = true;
    }
}
