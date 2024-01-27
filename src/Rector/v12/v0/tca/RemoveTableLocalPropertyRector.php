<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\tca;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-98479-RemovedFileReferenceRelatedFunctionality.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\RemoveTableLocalPropertyRector\RemoveTableLocalPropertyRectorTest
 */
final class RemoveTableLocalPropertyRector extends AbstractRector
{
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    public function getNodeTypes(): array
    {
        return [ArrayItem::class];
    }

    /**
     * @param ArrayItem $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->key instanceof Expr) {
            return null;
        }

        if (! $this->valueResolver->isValue($node->key, 'foreign_match_fields')) {
            return null;
        }

        if (! $node->value instanceof Array_) {
            return null;
        }

        foreach ($node->value->items as $itemKey => $item) {
            if (! $item instanceof ArrayItem) {
                continue;
            }

            if (! $item->key instanceof Expr) {
                continue;
            }

            if ($this->valueResolver->isValue($item->key, 'table_local')) {
                unset($node->value->items[$itemKey]);
            }
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove TCA property table_local in foreign_match_fields', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

return [
    'columns' => [
        'images' => [
            'config' => ExtensionManagementUtility::getFileFieldTCAConfig(
                'images',
                [
                    'foreign_match_fields' => [
                        'fieldname' => 'media',
                        'tablenames' => 'tx_site_domain_model_mediacollection',
                        'table_local' => 'sys_file',
                    ],
                    'maxitems' => 1,
                    'minitems' => 1,
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

return [
    'columns' => [
        'images' => [
            'config' => ExtensionManagementUtility::getFileFieldTCAConfig(
                'images',
                [
                    'foreign_match_fields' => [
                        'fieldname' => 'media',
                        'tablenames' => 'tx_site_domain_model_mediacollection',
                    ],
                    'maxitems' => 1,
                    'minitems' => 1,
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
    ],
];
CODE_SAMPLE
        )]);
    }
}
