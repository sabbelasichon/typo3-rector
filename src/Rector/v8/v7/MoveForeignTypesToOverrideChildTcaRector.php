<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v7;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.7/Deprecation-80000-InlineOverrideChildTca.html?highlight=foreign_types
 */
final class MoveForeignTypesToOverrideChildTcaRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @var string
     */
    private const FOREIGN_TYPES = 'foreign_types';

    /**
     * @var string
     */
    private const OVERRIDE_CHILD_TCA = 'overrideChildTca';

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('TCA InlineOverrideChildTca', [
            new CodeSample(<<<'PHP'
return [
    'columns' => [
        'aField' => [
            'config' => [
                'type' => 'inline',
                'foreign_types' => [
                    'aForeignType' => [
                        'showitem' => 'aChildField',
                    ],
                ],
            ],
        ],
    ],
];
PHP
                , <<<'PHP'
return [
    'columns' => [
        'aField' => [
            'config' => [
                'type' => 'inline',
                'overrideChildTca' => [
                    'types' => [
                        'aForeignType' => [
                            'showitem' => 'aChildField',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
PHP
            ),
        ]);
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
     * @return ?Return_
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isTca($node)) {
            return null;
        }

        $columns = $this->extractColumns($node);

        if (! $columns instanceof ArrayItem) {
            return null;
        }

        if (! $columns->value instanceof Array_) {
            return null;
        }

        foreach ($this->extractColumnConfig($columns->value) as $columnConfig) {
            //handle the special case of ExtensionManagementUtility::getFileFieldTCAConfig
            $columnConfig = $this->extractConfigFromGetFileFieldTcaConfig($columnConfig);

            if (! $columnConfig instanceof Array_) {
                continue;
            }

            $foreignTypesArrayItem = null;
            $overrideChildTcaNode = null;
            foreach ($columnConfig->items as $configItemValue) {
                if (! $configItemValue instanceof ArrayItem) {
                    continue;
                }

                if (null === $configItemValue->key) {
                    continue;
                }

                if ($this->isValue($configItemValue->key, self::FOREIGN_TYPES)) {
                    $foreignTypesArrayItem = $configItemValue;
                } elseif ($this->isValue($configItemValue->key, self::OVERRIDE_CHILD_TCA)) {
                    $overrideChildTcaNode = $configItemValue->value;
                }
            }

            // don't search further if no foreign_types is configured
            if (null === $foreignTypesArrayItem) {
                continue;
            }

            if (! $foreignTypesArrayItem->value instanceof Array_) {
                continue;
            }

            if (null !== $overrideChildTcaNode && ! $overrideChildTcaNode instanceof Array_) {
                continue;
            }

            if (null === $overrideChildTcaNode) {
                $overrideChildTcaNode = new Array_();
                $columnConfig->items[] = new ArrayItem($overrideChildTcaNode, new String_(
                    self::OVERRIDE_CHILD_TCA
                ));
            }

            // search for an existing overrideChildTca['types']
            $overrideChildTcaTypesArrayItem = null;
            foreach ($overrideChildTcaNode->items as $overrideChildTcaOption) {
                if (! $overrideChildTcaOption instanceof ArrayItem) {
                    continue;
                }
                if (null === $overrideChildTcaOption->key) {
                    continue;
                }
                if ($this->isValue($overrideChildTcaOption->key, 'types')) {
                    $overrideChildTcaTypesArrayItem = $overrideChildTcaOption;
                }
            }

            if (null === $overrideChildTcaTypesArrayItem) {
                $overrideChildTcaTypesArrayItem = new ArrayItem($foreignTypesArrayItem->value, new String_(
                    'types'
                ));
                $overrideChildTcaNode->items[] = $overrideChildTcaTypesArrayItem;
            } else {
                if (! $overrideChildTcaTypesArrayItem->value instanceof Array_) {
                    continue;
                }
                foreach ($foreignTypesArrayItem->value->items as $item) {
                    $overrideChildTcaTypesArrayItem->value->items[] = $item;
                }
            }

            $this->removeNode($foreignTypesArrayItem);
        }

        return $node;
    }

    private function extractConfigFromGetFileFieldTcaConfig(Node $columnConfig): Node
    {
        if ($columnConfig instanceof StaticCall) {
            if (! $this->isMethodStaticCallOrClassMethodObjectType(
                $columnConfig,
                ExtensionManagementUtility::class
            )) {
                return $columnConfig;
            }
            if (! $this->isName($columnConfig->name, 'getFileFieldTCAConfig')) {
                return $columnConfig;
            }
            if (count($columnConfig->args) < 2) {
                return $columnConfig;
            }
            if (! $columnConfig->args[1]->value instanceof Array_) {
                return $columnConfig;
            }
            return $columnConfig->args[1]->value;
        }
        return $columnConfig;
    }
}
