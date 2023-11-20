<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\tca;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Ssch\TYPO3Rector\Helper\StringUtility;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Ssch\TYPO3Rector\Rector\Tca\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97193-NewTCATypeNumber.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateEvalIntAndDouble2ToTypeNumberRector\MigrateEvalIntAndDouble2ToTypeNumberRectorTest
 */
final class MigrateEvalIntAndDouble2ToTypeNumberRector extends AbstractTcaRector
{
    use TcaHelperTrait;

    /**
     * @var string
     */
    private const INT = 'int';

    /**
     * @var string
     */
    private const DOUBLE2 = 'double2';

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate eval int and double2 to type number', [new CodeSample(
            <<<'CODE_SAMPLE'
'int_field' => [
    'label' => 'int field',
    'config' => [
        'type' => 'input',
        'eval' => 'int',
    ],
],
'double2_field' => [
    'label' => 'double2 field',
    'config' => [
        'type' => 'input',
        'eval' => 'double2',
    ],
],
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
'int_field' => [
    'label' => 'int field',
    'config' => [
        'type' => 'number',
    ],
],
'double2_field' => [
    'label' => 'double2 field',
    'config' => [
        'type' => 'number',
        'format' => 'decimal',
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

        // Return early, if not TCA type "input" or a renderType is set
        // or neither eval=int nor eval=double2 are set.
        if (! $this->isConfigType($configArray, 'input') || $this->hasRenderType($configArray)) {
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

        if (! StringUtility::inList($evalListValue, self::INT) && ! StringUtility::inList($evalListValue, self::DOUBLE2)) {
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
            $evalArrayItem->value = new String_(implode(',', $evalList));
        } else {
            // 'eval' is empty, remove whole configuration
            $this->removeNode($evalArrayItem);
        }

        $toChangeArrayItem = $this->extractArrayItemByKey($configArray, 'type');
        if ($toChangeArrayItem instanceof ArrayItem) {
            $toChangeArrayItem->value = new String_('number');
        }

        if (StringUtility::inList($evalListValue, self::DOUBLE2)) {
            $configArray->items[] = new ArrayItem(new String_('decimal'), new String_('format'));
        }

        $this->hasAstBeenChanged = true;
    }
}
