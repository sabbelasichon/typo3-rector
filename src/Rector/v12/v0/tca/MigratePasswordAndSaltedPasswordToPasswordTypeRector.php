<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\tca;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Ssch\TYPO3Rector\Helper\StringUtility;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Ssch\TYPO3Rector\Rector\Tca\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97104-NewTCATypePassword.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigratePasswordAndSaltedPasswordToPasswordTypeRector\MigratePasswordAndSaltedPasswordToPasswordTypeRectorTest
 */
final class MigratePasswordAndSaltedPasswordToPasswordTypeRector extends AbstractTcaRector
{
    use TcaHelperTrait;

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
'password_field' => [
    'label' => 'Password',
    'config' => [
        'type' => 'input',
        'eval' => 'trim,password,saltedPassword',
    ],
],
'another_password_field' => [
    'label' => 'Password',
    'config' => [
        'type' => 'input',
        'eval' => 'trim,password',
    ],
],
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
'password_field' => [
    'label' => 'Password',
    'config' => [
        'type' => 'password',
    ],
],
'another_password_field' => [
    'label' => 'Password',
    'config' => [
        'type' => 'password',
        'hashed' => false,
    ],
],
CODE_SAMPLE
        )]);
    }

    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        $configArray = $this->extractSubArrayByKey($columnTca, self::CONFIG);
        if (! $configArray instanceof Array_) {
            return;
        }

        // Early return in case column is not of type=input
        if (! $this->isConfigType($configArray, 'input')) {
            return;
        }

        if (! $this->hasKey($configArray, 'eval')) {
            return;
        }

        $evalArrayItem = $this->extractArrayItemByKey($configArray, 'eval');
        if (! $evalArrayItem instanceof ArrayItem) {
            return;
        }

        $evalListValue = $this->valueResolver->getValue($evalArrayItem->value);
        if (! is_string($evalListValue)) {
            return;
        }

        if (! StringUtility::inList($evalListValue, self::PASSWORD)
            && ! StringUtility::inList($evalListValue, self::SALTED_PASSWORD)
        ) {
            return;
        }

        // Set the TCA type to "password"
        $this->changeTcaType($configArray, self::PASSWORD);

        // Remove 'max' and 'search' config
        $this->removeArrayItemFromArrayByKey($configArray, 'max');
        $this->removeArrayItemFromArrayByKey($configArray, 'search');

        $evalList = ArrayUtility::trimExplode(',', $evalListValue, true);

        // Disable password hashing, if eval=password is used standalone
        if (in_array('password', $evalList, true) && ! in_array('saltedPassword', $evalList, true)) {
            $configArray->items[] = new ArrayItem(new ConstFetch(new Name('false')), new String_('hashed'));
        }

        if (in_array('null', $evalList, true)) {
            // Set "eval" to "null", since it's currently defined and the only allowed "eval" for type=password
            $evalArrayItem->value = new String_('null');
        } else {
            // 'eval' is empty, remove whole configuration
            $this->removeNode($evalArrayItem);
        }

        $this->hasAstBeenChanged = true;
    }
}
