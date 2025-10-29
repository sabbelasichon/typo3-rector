<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v1;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Scalar\String_;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.1/Deprecation-103850-RenamedPageTreeNavigationComponentID.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v1\RenamePageTreeNavigationComponentIdRector\RenamePageTreeNavigationComponentIdRectorTest
 */
final class RenamePageTreeNavigationComponentIdRector extends AbstractRector implements DocumentedRuleInterface
{
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Renamed Page Tree Navigation Component ID', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'mymodule' => [
        'parent' => 'web',
        'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'mymodule' => [
        'parent' => 'web',
        'navigationComponent' => '@typo3/backend/tree/page-tree-element',
    ],
];
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ArrayItem::class];
    }

    /**
     * @param ArrayItem $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($this->file->getFilePath(), $node)) {
            return null;
        }

        $node->value = new String_('@typo3/backend/tree/page-tree-element');

        return $node;
    }

    private function shouldSkip(string $filePath, ArrayItem $node): bool
    {
        if (! str_ends_with($filePath, 'Configuration/Backend/Modules.php')) {
            return true;
        }

        if (! $node->key instanceof Expr) {
            return true;
        }

        if (! $this->valueResolver->isValue($node->key, 'navigationComponent')) {
            return true;
        }

        return ! $this->valueResolver->isValue($node->value, '@typo3/backend/page-tree/page-tree-element');
    }
}
