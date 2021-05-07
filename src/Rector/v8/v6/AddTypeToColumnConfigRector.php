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
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.6/Deprecation-79440-TcaChanges.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v6\AddTypeToColumnConfigRector\AddTypeToColumnConfigRectorTest
 */
final class AddTypeToColumnConfigRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @var string
     */
    private const TYPE = 'type';

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

        foreach ($columns->items as $columnArrayItem) {
            if (! $columnArrayItem instanceof ArrayItem) {
                continue;
            }

            $columnName = $columnArrayItem->key;
            if (null === $columnName) {
                continue;
            }

            if (! $columnArrayItem->value instanceof Array_) {
                continue;
            }

            $config = $this->extractSubArrayByKey($columnArrayItem->value, 'config');

            if (null === $config) {
                // found a column without a 'config' part. Create an empty 'config' array
                $config = new Array_();
                $columnArrayItem->value->items[] = new ArrayItem($config, new String_('config'));
                $hasAstBeenChanged = true;
            }

            if (null === $this->extractArrayItemByKey($config, self::TYPE)) {
                // found a column without a 'type' field in the config. add type => none
                $config->items[] = new ArrayItem(new String_('none'), new String_(self::TYPE));
                $hasAstBeenChanged = true;
            }
        }

        return $hasAstBeenChanged ? $node : null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add type to column config if not exists', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'bar' => []
    ]
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'bar' => [
            'config' => [
                'type' => 'none'
            ]
        ]
    ]
];
CODE_SAMPLE
        )]);
    }
}
