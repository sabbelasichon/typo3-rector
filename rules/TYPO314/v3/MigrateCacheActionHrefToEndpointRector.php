<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v3;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Cast\String_ as StringCast;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.3/Deprecation-109107-CacheActionHrefKey.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v3\MigrateCacheActionHrefToEndpointRector\MigrateCacheActionHrefToEndpointRectorTest
 */
final class MigrateCacheActionHrefToEndpointRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate the cache action key `href` to `endpoint`', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$event->addCacheAction([
    'id' => 'my_custom_cache',
    'href' => $uriBuilder->buildUriFromRoute('ajax_my_cache_clear'),
    'iconIdentifier' => 'actions-system-cache-clear',
    'title' => 'Clear my cache',
]);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$event->addCacheAction([
    'id' => 'my_custom_cache',
    'endpoint' => (string)$uriBuilder->buildUriFromRoute('ajax_my_cache_clear'),
    'iconIdentifier' => 'actions-system-cache-clear',
    'title' => 'Clear my cache',
]);
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isNames($node->name, ['addCacheAction', 'setCacheActions'])) {
            return null;
        }

        if (! $this->isObjectType(
            $node->var,
            new ObjectType('TYPO3\CMS\Backend\Backend\Event\ModifyClearCacheActionsEvent')
        )) {
            return null;
        }

        $firstArg = $node->getArgs()[0] ?? null;
        if ($firstArg === null || ! $firstArg->value instanceof Array_) {
            return null;
        }

        if ($this->isName($node->name, 'addCacheAction')) {
            return $this->migrateCacheAction($firstArg->value) ? $node : null;
        }

        $hasChanged = false;
        foreach ($firstArg->value->items as $item) {
            if (! $item instanceof ArrayItem || ! $item->value instanceof Array_) {
                continue;
            }

            if ($this->migrateCacheAction($item->value)) {
                $hasChanged = true;
            }
        }

        return $hasChanged ? $node : null;
    }

    private function migrateCacheAction(Array_ $cacheAction): bool
    {
        $hrefItem = null;
        foreach ($cacheAction->items as $item) {
            if (! $item instanceof ArrayItem || ! $item->key instanceof String_) {
                continue;
            }

            if ($item->key->value === 'endpoint') {
                return false;
            }

            if ($item->key->value === 'href') {
                $hrefItem = $item;
            }
        }

        if (! $hrefItem instanceof ArrayItem) {
            return false;
        }

        $hrefItem->key = new String_('endpoint');
        if (! $this->isStringExpr($hrefItem->value)) {
            $hrefItem->value = new StringCast($hrefItem->value);
        }

        return true;
    }

    private function isStringExpr(Expr $expr): bool
    {
        if ($expr instanceof String_ || $expr instanceof StringCast) {
            return true;
        }

        return $this->getType($expr)
            ->isString()
            ->yes();
    }
}
