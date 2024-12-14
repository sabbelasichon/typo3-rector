<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Ssch\TYPO3Rector\Helper\StringUtility;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97013-NewTCATypeEmail.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateToEmailTypeRector\MigrateToEmailTypeRectorTest
 */
final class MigrateToEmailTypeRector extends AbstractTcaRector implements DocumentedRuleInterface
{
    /**
     * @var string
     */
    private const EMAIL = 'email';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrates existing input TCA with eval email to new TCA type email', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'email_field' => [
            'label' => 'Email',
            'config' => [
                'type' => 'input',
                'eval' => 'trim,email',
                'max' => 255,
            ],
        ],
    ],
];

CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'email_field' => [
            'label' => 'Email',
            'config' => [
                'type' => 'email',
            ],
        ],
    ],
];
CODE_SAMPLE
        )]);
    }

    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        $configArrayItem = $this->extractArrayItemByKey($columnTca, self::CONFIG);
        if (! $configArrayItem instanceof ArrayItem) {
            return;
        }

        $configArray = $configArrayItem->value;
        if (! $configArray instanceof Array_) {
            return;
        }

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

        if (! StringUtility::inList($evalListValue, self::EMAIL)) {
            return;
        }

        $this->removeArrayItemFromArrayByKey($configArray, 'max');

        $evalList = ArrayUtility::trimExplode(',', $evalListValue, true);

        // Remove "email" and "trim" from $evalList
        $evalList = array_filter($evalList, static fn (string $eval) => $eval !== self::EMAIL && $eval !== 'trim');

        if ($evalList !== []) {
            // Write back filtered 'eval'
            $evalArrayItem->value = new String_(implode(',', $evalList));
        } else {
            $this->removeArrayItemFromArrayByKey($configArray, 'eval');
        }

        $this->changeTcaType($configArray, self::EMAIL);

        $this->hasAstBeenChanged = true;
    }
}
