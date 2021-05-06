<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v6;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.6/Deprecation-79440-TcaChanges.html#suggest-wizard
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v6\MoveTypeGroupSuggestWizzardToSuggestOptions\MoveTypeGroupSuggestWizzardToSuggestOptionsTest
 */
final class MoveTypeGroupSuggestWizzardToSuggestOptionsRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @var string
     */
    private const TYPE = 'type';

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate the "suggest" wizard in type=group to "hideSuggest" and "suggestOptions"', [
            new CodeSample(
                <<<'CODE_SAMPLE'
[
    'columns' => [
        'group_db_8' => [
            'label' => 'group_db_8',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_styleguide_staticdata',
                'wizards' => [
                    '_POSITION' => 'top',
                    'suggest' => [
                        'type' => 'suggest',
                        'default' => [
                            'pidList' => 42,
                        ],
                    ],
                ],
            ],
        ],
    ],
];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
[
    'columns' => [
        'group_db_8' => [
            'label' => 'group_db_8',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_styleguide_staticdata',
                'suggestOptions' => [
                    'default' => [
                        'pidList' => 42,
                    ]
                ],
            ],
        ],
    ],
];
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
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

        $columns = $this->extractSubArrayByKey($node->expr, 'columns');
        if (null === $columns) {
            return null;
        }

        $hasAstBeenChanged = false;

        foreach ($this->extractColumnConfig($columns) as $config) {
            if (! $config instanceof Array_) {
                continue;
            }

            if (! $this->hasKeyValuePair($config, self::TYPE, 'group')) {
                continue;
            }
            if (! $this->hasKeyValuePair($config, 'internal_type', 'db')) {
                continue;
            }

            $wizards = $this->extractSubArrayByKey($config, 'wizards');

            if (null === $wizards) {
                continue;
            }

            foreach ($this->extractSubArraysWithArrayItemMatching($wizards, self::TYPE, 'suggest') as $wizard) {
                $wizardConfig = $wizard->value;
                $typeItem = $this->extractArrayItemByKey($wizardConfig, self::TYPE);
                if (null !== $typeItem) {
                    $this->removeNode($typeItem);
                }
                $config->items[] = new ArrayItem($wizardConfig, new String_('suggestOptions'));
                $this->removeNode($wizard);
                $hasAstBeenChanged = true;
            }
        }

        return $hasAstBeenChanged ? $node : null;
    }
}
