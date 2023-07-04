<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\tca;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use Ssch\TYPO3Rector\Helper\StringUtility;
use Ssch\TYPO3Rector\Rector\Tca\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97013-NewTCATypeEmail.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateToEmailTypeRector\MigrateToEmailTypeRectorTest
 */
final class MigrateToEmailTypeRector extends AbstractTcaRector
{
    /**
     * @var string
     */
    private const EMAIL = 'email';

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrates existing input TCA with eval email to new type', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [],
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
    'ctrl' => [],
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
        $configArray = $this->extractArrayItemByKey($columnTca, self::CONFIG);

        if (! $configArray instanceof ArrayItem) {
            return;
        }

        $configArrayValue = $configArray->value;

        if (! $configArrayValue instanceof Array_) {
            return;
        }

        if (! $this->hasKey($configArrayValue, 'eval')) {
            return;
        }

        $evalArrayItem = $this->extractArrayItemByKey($configArrayValue, 'eval');

        if (! $evalArrayItem instanceof ArrayItem) {
            return;
        }

        $value = $this->valueResolver->getValue($evalArrayItem->value);

        if (! is_string($value)) {
            return;
        }

        if (! StringUtility::inList($value, self::EMAIL)) {
            return;
        }

        $configArray->value = $this->nodeFactory->createArray([
            'type' => self::EMAIL,
        ]);

        $this->hasAstBeenChanged = true;
    }
}
