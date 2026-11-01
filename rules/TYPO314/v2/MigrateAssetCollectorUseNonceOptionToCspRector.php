<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v2;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.2/Deprecation-100887-UseNonceArgumentInFAssetViewHelpers.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateAssetCollectorUseNonceOptionToCspRector\MigrateAssetCollectorUseNonceOptionToCspRectorTest
 */
final class MigrateAssetCollectorUseNonceOptionToCspRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate "useNonce" option of AssetCollector to "csp"', [new CodeSample(
            <<<'CODE_SAMPLE'
$assetCollector = GeneralUtility::makeInstance(AssetCollector::class);
$assetCollector->addJavaScript('my-script', $src, [], ['useNonce' => true]);
$assetCollector->addStyleSheet('my-style', $src, [], ['useNonce' => true]);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$assetCollector = GeneralUtility::makeInstance(AssetCollector::class);
$assetCollector->addJavaScript('my-script', $src, [], ['csp' => true]);
$assetCollector->addStyleSheet('my-style', $src, [], ['csp' => true]);
CODE_SAMPLE
        )]);
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
        if (! $this->isNames($node->name, ['addJavaScript', 'addStyleSheet'])) {
            return null;
        }

        if (! $this->isObjectType($node->var, new ObjectType('TYPO3\CMS\Core\Page\AssetCollector'))) {
            return null;
        }

        $args = $node->getArgs();
        if (! isset($args[3]) || ! $args[3]->value instanceof Array_) {
            return null;
        }

        $useNonceItem = null;
        foreach ($args[3]->value->items as $item) {
            if (! $item instanceof ArrayItem || ! $item->key instanceof String_) {
                continue;
            }

            if ($item->key->value === 'csp') {
                return null;
            }

            if ($item->key->value === 'useNonce') {
                $useNonceItem = $item;
            }
        }

        if (! $useNonceItem instanceof ArrayItem) {
            return null;
        }

        $useNonceItem->key = new String_('csp');

        return $node;
    }
}
