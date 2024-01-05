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
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97013-NewTCATypeEmail.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\flexform\MigrateEmailFlagToEmailTypeFlexFormRector\MigrateEmailFlagToEmailTypeFlexFormRectorTest
 */
final class MigrateEmailFlagToEmailTypeFlexFormRector extends AbstractFlexFormRector implements FlexFormRectorInterface
{
    use FlexFormHelperTrait;

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate email flag to email type', [new CodeSample(
            <<<'CODE_SAMPLE'
<T3DataStructure>
    <ROOT>
        <sheetTitle>aTitle</sheetTitle>
        <type>array</type>
        <el>
            <email_field>
                <label>Email</label>
                <config>
                    <type>input</type>
                    <eval>trim,email</eval>
                    <max>255</max>
                </config>
            </email_field>
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
            <email_field>
                <label>Email</label>
                <config>
                    <type>email</type>
                </config>
            </email_field>
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

        if (! StringUtility::inList($evalListValue, 'email')) {
            return;
        }

        // Set the TCA type to "email"
        $this->changeTcaType($this->domDocument, $configElement, 'email');

        $this->removeChildElementFromDomElementByKey($configElement, 'max');

        $evalList = ArrayUtility::trimExplode(',', $evalListValue, true);

        // Remove "null" and "trim" from $evalList
        $evalList = array_filter($evalList, static fn (string $eval) => $eval !== 'email' && $eval !== 'trim');

        if ($evalList !== []) {
            // Write back filtered 'eval'
            $evalDomElement->nodeValue = '';
            $evalDomElement->appendChild($this->domDocument->createTextNode(implode(',', $evalList)));
        } elseif ($evalDomElement->parentNode instanceof DOMElement) {
            // 'eval' is empty, remove whole configuration
            $evalDomElement->parentNode->removeChild($evalDomElement);
        }

        $this->domDocumentHasBeenChanged = true;
    }
}
