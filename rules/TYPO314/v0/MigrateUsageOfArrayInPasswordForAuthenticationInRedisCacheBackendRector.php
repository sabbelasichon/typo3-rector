<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-107725-RedisCacheBackendAuthenticationWithUsernameAndPassword.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateUsageOfArrayInPasswordForAuthenticationInRedisCacheBackendRector\MigrateUsageOfArrayInPasswordForAuthenticationInRedisCacheBackendRectorTest
 */
final class MigrateUsageOfArrayInPasswordForAuthenticationInRedisCacheBackendRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate usage of array in password for authentication in Redis cache backend', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['pages']['backend'] = \TYPO3\CMS\Core\Cache\Backend\RedisBackend::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['pages']['options'] = [
    'defaultLifetime' => 86400,
    'database' => 0,
    'hostname' => 'redis',
    'port' => 6379,
    'password' => [
        'user' => 'redis',
        'pass' => 'redis',
    ]
];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['pages']['backend'] = \TYPO3\CMS\Core\Cache\Backend\RedisBackend::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['pages']['options'] = [
    'defaultLifetime' => 86400,
    'database' => 0,
    'hostname' => 'redis',
    'port' => 6379,
    'username' => 'redis',
    'password' => 'redis',
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
        return [Assign::class];
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->var instanceof ArrayDimFetch) {
            return null;
        }

        if (! $this->isConfVarOptions($node->var)) {
            return null;
        }

        if (! $node->expr instanceof Array_) {
            return null;
        }

        $optionsArray = $node->expr;
        $passwordItem = null;
        $passwordItemKey = null;

        foreach ($optionsArray->items as $key => $item) {
            if ($item instanceof ArrayItem && $item->key instanceof String_ && $item->key->value === 'password') {
                $passwordItem = $item;
                $passwordItemKey = $key;
                break;
            }
        }

        if (! $passwordItem instanceof ArrayItem || ! $passwordItem->value instanceof Array_) {
            return null;
        }

        $passwordArray = $passwordItem->value;
        $userItem = null;
        $passItem = null;

        foreach ($passwordArray->items as $item) {
            if ($item instanceof ArrayItem && $item->key instanceof String_) {
                if ($item->key->value === 'user') {
                    $userItem = $item;
                } elseif ($item->key->value === 'pass') {
                    $passItem = $item;
                }
            }
        }

        // The 'pass' key is mandatory for a transformation
        if (! $passItem instanceof ArrayItem) {
            return null;
        }

        // If a 'user' is provided, add the 'username' item
        if ($userItem instanceof ArrayItem) {
            $optionsArray->items[] = new ArrayItem($userItem->value, new String_('username'));
        }

        // Update the 'password' item to be a simple string value
        if ($passwordItemKey !== null) {
            $optionsArray->items[$passwordItemKey] = new ArrayItem($passItem->value, new String_('password'));
        }

        return $node;
    }

    private function isConfVarOptions(ArrayDimFetch $arrayDimFetch): bool
    {
        $path = [];
        $currentNode = $arrayDimFetch;

        while ($currentNode instanceof ArrayDimFetch) {
            if ($currentNode->dim instanceof String_) {
                $path[] = $currentNode->dim->value;
            } else {
                return false;
            }

            $currentNode = $currentNode->var;
        }

        if (! $this->isName($currentNode, 'GLOBALS')) {
            return false;
        }

        $path = array_reverse($path);

        // We are looking for TYPO3_CONF_VARS.SYS.caching.cacheConfigurations.*.options
        if (count($path) !== 6) {
            return false;
        }

        return $path[0] === 'TYPO3_CONF_VARS'
            && $path[1] === 'SYS'
            && $path[2] === 'caching'
            && $path[3] === 'cacheConfigurations'
            // $path[4] is the dynamic cache name (e.g., 'pages')
            && $path[5] === 'options';
    }
}
