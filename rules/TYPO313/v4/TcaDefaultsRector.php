<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v4;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Contract\NoChangelogRequiredInterface;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v4\TcaDefaultsRector\TcaDefaultsRectorTest
 */
final class TcaDefaultsRector extends AbstractTcaRector implements DocumentedRuleInterface, NoChangelogRequiredInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add a default value to TCA fields if missing', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'nullable_column' => [
            'config' => [
                'type' => 'input',
            ],
        ],
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'nullable_column' => [
            'config' => [
                'type' => 'input',
                'default' => '',
            ],
        ],
    ],
];
CODE_SAMPLE
        )]);
    }

    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        $configArray = $this->extractSubArrayByKey($columnTca, self::CONFIG);
        if (! $configArray instanceof Array_) {
            return;
        }

        if ($this->hasKey($configArray, 'default')) {
            return;
        }

        if ($this->hasKeyValuePair($configArray, 'nullable', true)) {
            $configArray->items[] = new ArrayItem(new ConstFetch(new Name('null')), new String_('default'));
            $this->hasAstBeenChanged = true;
            return;
        }

        if ($this->isConfigType($configArray, 'input')
            || $this->isConfigType($configArray, 'color')
            || $this->isConfigType($configArray, 'email')
            || $this->isConfigType($configArray, 'link')
            || $this->isConfigType($configArray, 'password')
            || $this->isConfigType($configArray, 'slug')
            || $this->isConfigType($configArray, 'text')
        ) {
            $configArray->items[] = new ArrayItem(new String_(''), new String_('default'));
            $this->hasAstBeenChanged = true;
            return;
        }

        if ($this->isConfigType($configArray, 'number')
            || $this->isConfigType($configArray, 'datetime')
            || $this->isConfigType($configArray, 'check')
            || $this->isConfigType($configArray, 'radio')
            || $this->isConfigType($configArray, 'inline')
            || $this->isConfigType($configArray, 'select')
            || $this->isConfigType($configArray, 'group')
        ) {
            $configArray->items[] = new ArrayItem(new Int_(0), new String_('default'));
            $this->hasAstBeenChanged = true;
        }
    }
}
