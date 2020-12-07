<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v6;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.6/Deprecation-78899-TCACtrlFieldRequestUpdateDropped.html
 */
final class MoveRequestUpdateOptionFromControlToColumnsRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('TCA ctrl field requestUpdate dropped', [new CodeSample(<<<'PHP'
return [
    'ctrl' => [
        'requestUpdate' => 'foo',
    ],
    'columns' => [
        'foo' => []
    ]
];
PHP
                , <<<'PHP'
return [
    'ctrl' => [
    ],
    'columns' => [
        'foo' => [
            'onChange' => 'reload'
        ]
    ]
];
PHP
            )]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [Return_::class];
    }

    /**
     * @param Return_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isTca($node)) {
            return null;
        }

        $ctrl = $this->extractCtrl($node);

        if (! $ctrl instanceof ArrayItem) {
            return null;
        }

        $ctrlItems = $ctrl->value;

        if (! $ctrlItems instanceof Array_) {
            return null;
        }

        $requestUpdateFields = [];

        foreach ($ctrlItems->items as $fieldValue) {
            if (! $fieldValue instanceof ArrayItem) {
                continue;
            }

            if (null === $fieldValue->key) {
                continue;
            }

            if ($this->isValue($fieldValue->key, 'requestUpdate')) {
                $fields = $this->getValue($fieldValue->value);
                if (null === $fields) {
                    return null;
                }
                $requestUpdateFields = GeneralUtility::trimExplode(',', $fields);
                $this->removeNode($fieldValue);
            }
        }

        if ([] === $requestUpdateFields) {
            return null;
        }

        $columns = $this->extractColumns($node);

        if (! $columns instanceof ArrayItem) {
            return null;
        }

        $columnItems = $columns->value;

        if (! $columnItems instanceof Array_) {
            return null;
        }

        foreach ($columnItems->items as $columnItem) {
            if (! $columnItem instanceof ArrayItem) {
                continue;
            }

            if (null === $columnItem->key) {
                continue;
            }

            $fieldName = $this->getValue($columnItem->key);

            if (! in_array($fieldName, $requestUpdateFields, true)) {
                continue;
            }

            if (! $columnItem->value instanceof Array_) {
                continue;
            }

            $columnItem->value->items[] = new ArrayItem(new String_('reload'), new String_('onChange'));
        }

        // change the node
        return $node;
    }
}
