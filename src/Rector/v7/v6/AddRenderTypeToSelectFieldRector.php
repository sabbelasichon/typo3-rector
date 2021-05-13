<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v7\v6;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Exception\ShouldNotHappenException;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Ssch\TYPO3Rector\Rector\Tca\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/7.6/Deprecation-69822-DeprecateSelectFieldTca.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v7\v6\AddRenderTypeToSelectFieldRector\AddRenderTypeToSelectFieldRectorTest
 */
final class AddRenderTypeToSelectFieldRector extends AbstractTcaRector
{
    use TcaHelperTrait;

    /**
     * @var string
     */
    private const RENDER_TYPE = 'renderType';

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add renderType for select fields', [
            new CodeSample(
                <<<'CODE_SAMPLE'
return [
    'ctrl' => [
    ],
    'columns' => [
        'sys_language_uid' => [
            'config' => [
                'type' => 'select',
                'maxitems' => 1,
            ],
        ],
    ],
];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
return [
    'ctrl' => [
    ],
    'columns' => [
        'sys_language_uid' => [
            'config' => [
                'type' => 'select',
                'maxitems' => 1,
                'renderType' => 'selectSingle',
            ],
        ],
    ],
];
CODE_SAMPLE
            ),
        ]);
    }

    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        $config = $this->extractSubArrayByKey($columnTca, 'config');
        if (null === $config) {
            return;
        }

        if (! $this->hasKeyValuePair($config, self::TYPE, 'select')) {
            return;
        }

        if (null !== $this->extractArrayItemByKey($config, self::RENDER_TYPE)) {
            // If the renderType is already set, do nothing
            return;
        }

        $renderModeExpr = $this->extractArrayValueByKey($config, 'renderMode');
        if (null !== $renderModeExpr) {
            $renderType = null;

            if ($this->isValue($renderModeExpr, 'tree')) {
                $renderType = 'selectTree';
            } elseif ($this->valueResolver->isValue($renderModeExpr, 'singlebox')) {
                $renderType = 'selectSingleBox';
            } elseif ($this->valueResolver->isValue($renderModeExpr, 'checkbox')) {
                $renderType = 'selectCheckBox';
            } else {
                // ToDo: report this case instead of failing
                new ShouldNotHappenException(sprintf(
                    'The render mode %s is invalid for the select field in %s',
                    $this->valueResolver->getValue($renderModeExpr),
                    $this->valueResolver->getValue($columnName)
                ));
            }
        } else {
            $maxItemsExpr = $this->extractArrayValueByKey($config, 'maxitems');
            $maxItems = null !== $maxItemsExpr ? $this->valueResolver->getValue($maxItemsExpr) : null;
            $renderType = $maxItems <= 1 ? 'selectSingle' : 'selectMultipleSideBySide';
        }

        if (null !== $renderType) {
            $renderTypeItem = new ArrayItem(new String_($renderType), new String_(self::RENDER_TYPE));
            $this->insertItemAfterKey($config, $renderTypeItem, self::TYPE);
            $this->hasAstBeenChanged = true;
        }
    }
}
