<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v2;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.2/Deprecation-107802-DeprecateUsageOfArrayInPasswordForAuthenticationInRedisSessionBackend.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateUsageOfArrayInPasswordForAuthenticationInRedisSessionBackendRector\MigrateUsageOfArrayInPasswordForAuthenticationInRedisSessionBackendRectorTest
 */
final class MigrateUsageOfArrayInPasswordForAuthenticationInRedisSessionBackendRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate usage of array in password for authentication in Redis session backend', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['SYS']['session']['BE'] = [
    'backend' => \TYPO3\CMS\Core\Session\Backend\RedisSessionBackend::class,
    'options' => [
        'database' => 0,
        'hostname' => 'redis',
        'port' => 6379,
        'password' => [
            'user' => 'redis',
            'pass' => 'redis',
        ],
    ],
];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['SYS']['session']['BE'] = [
    'backend' => \TYPO3\CMS\Core\Session\Backend\RedisSessionBackend::class,
    'options' => [
        'database' => 0,
        'hostname' => 'redis',
        'port' => 6379,
        'password' => 'redis',
        'username' => 'redis',
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

        if (! $node->expr instanceof Array_) {
            return null;
        }

        $path = $this->resolveSessionConfigurationPath($node->var);
        if ($path === null) {
            return null;
        }

        $optionsArray = $node->expr;
        if ($path === '') {
            $optionsItem = $this->findArrayItem($node->expr, 'options');
            if (! $optionsItem instanceof ArrayItem) {
                return null;
            }

            $optionsArray = $optionsItem->value;
        }

        if (! $optionsArray instanceof Array_) {
            return null;
        }

        if (! $this->migratePassword($optionsArray)) {
            return null;
        }

        return $node;
    }

    private function migratePassword(Array_ $optionsArray): bool
    {
        $passwordItem = $this->findArrayItem($optionsArray, 'password');
        if (! $passwordItem instanceof ArrayItem || ! $passwordItem->value instanceof Array_) {
            return false;
        }

        $userItem = $this->findArrayItem($passwordItem->value, 'user');
        $passItem = $this->findArrayItem($passwordItem->value, 'pass');
        if (! $passItem instanceof ArrayItem) {
            return false;
        }

        $passwordItem->value = $passItem->value;

        if (! $userItem instanceof ArrayItem) {
            return true;
        }

        $usernameItem = $this->findArrayItem($optionsArray, 'username');
        if ($usernameItem instanceof ArrayItem) {
            $usernameItem->value = $userItem->value;
        } else {
            $optionsArray->items[] = new ArrayItem($userItem->value, new String_('username'));
        }

        return true;
    }

    /**
     * Returns '' for TYPO3_CONF_VARS.SYS.session.<BE|FE> and 'options' for its options sub key.
     */
    private function resolveSessionConfigurationPath(ArrayDimFetch $arrayDimFetch): ?string
    {
        $path = [];
        $currentNode = $arrayDimFetch;

        while ($currentNode instanceof ArrayDimFetch) {
            if (! $currentNode->dim instanceof String_) {
                return null;
            }

            $path[] = $currentNode->dim->value;
            $currentNode = $currentNode->var;
        }

        if (! $this->isName($currentNode, 'GLOBALS')) {
            return null;
        }

        $path = array_reverse($path);
        $pathLength = count($path);
        if ($pathLength !== 4 && $pathLength !== 5) {
            return null;
        }

        if ($path[0] !== 'TYPO3_CONF_VARS' || $path[1] !== 'SYS' || $path[2] !== 'session') {
            return null;
        }

        if (! in_array($path[3], ['BE', 'FE'], true)) {
            return null;
        }

        if ($pathLength === 4) {
            return '';
        }

        return $path[4] === 'options' ? 'options' : null;
    }

    private function findArrayItem(Array_ $array, string $key): ?ArrayItem
    {
        foreach ($array->items as $item) {
            if ($item instanceof ArrayItem && $item->key instanceof String_ && $item->key->value === $key) {
                return $item;
            }
        }

        return null;
    }
}
