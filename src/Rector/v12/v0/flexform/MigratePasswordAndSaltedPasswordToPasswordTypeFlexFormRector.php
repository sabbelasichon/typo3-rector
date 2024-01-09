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
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97159-NewTCATypeLink.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\flexform\MigratePasswordAndSaltedPasswordToPasswordTypeFlexFormRector\MigratePasswordAndSaltedPasswordToPasswordTypeFlexFormRectorTest
 */
final class MigratePasswordAndSaltedPasswordToPasswordTypeFlexFormRector extends AbstractFlexFormRector implements FlexFormRectorInterface
{
    use FlexFormHelperTrait;

    /**
     * @var string
     */
    private const PASSWORD = 'password';

    /**
     * @var string
     */
    private const SALTED_PASSWORD = 'saltedPassword';

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate password and salted password to password type', [new CodeSample(
            <<<'CODE_SAMPLE'
<T3DataStructure>
    <ROOT>
        <sheetTitle>aTitle</sheetTitle>
        <type>array</type>
        <el>
            <password_field>
                <label>Password</label>
                <config>
                    <type>input</type>
                    <eval>trim,password,saltedPassword</eval>
                </config>
            </password_field>
            <another_password_field>
                <label>Password</label>
                <config>
                    <type>input</type>
                    <eval>trim,password</eval>
                </config>
            </another_password_field>
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
            <password_field>
                <label>Password</label>
                <config>
                    <type>password</type>
                </config>
            </password_field>
            <another_password_field>
                <label>Password</label>
                <config>
                    <type>password</type>
                    <hashed>false</hashed>
                </config>
            </another_password_field>
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

        if (! StringUtility::inList($evalListValue, self::PASSWORD)
            && ! StringUtility::inList($evalListValue, self::SALTED_PASSWORD)
        ) {
            return;
        }

        // Set the TCA type to "password"
        $this->changeTcaType($this->domDocument, $configElement, self::PASSWORD);

        // Remove 'max' and 'search' config
        $this->removeChildElementFromDomElementByKey($configElement, 'max');
        $this->removeChildElementFromDomElementByKey($configElement, 'search');

        $evalList = ArrayUtility::trimExplode(',', $evalListValue, true);

        // Disable password hashing, if eval=password is used standalone
        if (in_array('password', $evalList, true) && ! in_array('saltedPassword', $evalList, true)) {
            $configElement->appendChild($this->domDocument->createElement('hashed', '0'));
        }

        if (in_array('null', $evalList, true)) {
            // Set "eval" to "null", since it's currently defined and the only allowed "eval" for type=password
            $evalDomElement->nodeValue = '';
            $evalDomElement->appendChild($this->domDocument->createTextNode('null'));
        } elseif ($evalDomElement->parentNode instanceof DOMElement) {
            // 'eval' is empty, remove whole configuration
            $evalDomElement->parentNode->removeChild($evalDomElement);
        }

        $this->domDocumentHasBeenChanged = true;
    }
}
