<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v5;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.5/Deprecation-78524-TCAOptionVersioning_followPagesRemoved.html
 */
final class RemoveOptionVersioningFollowPagesRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('TCA option versioning_followPages removed', [new CodeSample(<<<'PHP'
return [
    'ctrl' => [
        'versioningWS' => 2,
        'versioning_followPages' => TRUE,
    ],
    'columns' => [
    ]
];
PHP
                , <<<'PHP'
return [
    'ctrl' => [
        'versioningWS' => true,
    ],
    'columns' => [
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

        foreach ($ctrlItems->items as $fieldValue) {
            if (! $fieldValue instanceof ArrayItem) {
                continue;
            }

            if (null === $fieldValue->key) {
                continue;
            }

            if ($this->isValue($fieldValue->key, 'versioning_followPages')) {
                $this->removeNode($fieldValue);
            } elseif ($this->isValue($fieldValue->key, 'versioningWS')) {
                $versioningWS = $this->getValue($fieldValue->value);
                if (! is_bool($versioningWS)) {
                    $fieldValue->value = (bool) $versioningWS ? $this->createTrue() : $this->createFalse();
                }
            }
        }

        return $node;
    }
}
