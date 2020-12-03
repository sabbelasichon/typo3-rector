<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.0/Breaking-87937-TCAOption_selicon_field_path_removed.html
 */
final class RemoveSeliconFieldPathRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('TCA option "selicon_field_path" removed', [new CodeSample(<<<'PHP'
return [
    'ctrl' => [
        'selicon_field' => 'icon',
        'selicon_field_path' => 'uploads/media'
    ],
];
PHP
                , <<<'PHP'
return [
    'ctrl' => [
        'selicon_field' => 'icon',
    ],
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

        $items = $ctrl->value;

        if (! $items instanceof Array_) {
            return null;
        }

        foreach ($items->items as $fieldValue) {
            if (! $fieldValue instanceof ArrayItem) {
                continue;
            }

            if (null === $fieldValue->key) {
                continue;
            }

            if ($this->isValue($fieldValue->key, 'selicon_field_path')) {
                $this->removeNode($fieldValue);
            }
        }

        return null;
    }
}
