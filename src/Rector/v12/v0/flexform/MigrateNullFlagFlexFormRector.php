<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\flexform;

use DOMElement;
use Ssch\TYPO3Rector\Contract\FileProcessor\FlexForms\Rector\FlexFormRectorInterface;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Ssch\TYPO3Rector\Helper\FlexFormHelperTrait;
use Ssch\TYPO3Rector\Helper\StringUtility;
use Ssch\TYPO3Rector\Rector\FlexForm\AbstractFlexFormRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-97384-TCAOptionNullable.html
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97384-TCAOptionNullable.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\flexform\MigrateNullFlagFlexFormRector\MigrateNullFlagFlexFormRectorTest
 */
final class MigrateNullFlagFlexFormRector extends AbstractFlexFormRector implements FlexFormRectorInterface
{
    use FlexFormHelperTrait;

    /**
     * @var string
     */
    private const NULL = 'null';

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate null flag', [new CodeSample(
            <<<'CODE_SAMPLE'
<T3DataStructure>
    <ROOT>
        <sheetTitle>aTitle</sheetTitle>
        <type>array</type>
        <el>
            <aFlexField>
                <label>aFlexFieldLabel</label>
                <config>
                    <eval>null</eval>
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
                    <nullable>true</nullable>
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

        if (! StringUtility::inList($evalListValue, self::NULL)) {
            return;
        }

        $evalList = ArrayUtility::trimExplode(',', $evalListValue, true);

        // Remove "null" from $evalList
        $evalList = array_filter($evalList, static fn (string $eval) => $eval !== self::NULL);

        if ($evalList !== []) {
            // Write back filtered 'eval'
            $evalDomElement->nodeValue = '';
            $evalDomElement->appendChild($this->domDocument->createTextNode(implode(',', $evalList)));
        } elseif ($evalDomElement->parentNode instanceof DOMElement) {
            // 'eval' is empty, remove whole configuration
            $evalDomElement->parentNode->removeChild($evalDomElement);
        }

        $nullableDomElement = $this->extractDomElementByKey($configElement, 'nullable');
        if (! $nullableDomElement instanceof DOMElement) {
            $configElement->appendChild($this->domDocument->createElement('nullable', '1'));
        }

        $this->domDocumentHasBeenChanged = true;
    }
}
