<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v4;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.4/Deprecation-105297-DeprecateTableoptionsAndCollateConnectionConfiguration.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v4\RenameTableOptionsAndCollateConnectionConfigurationRector\RenameTableOptionsAndCollateConnectionConfigurationRectorTest
 */
final class RenameTableOptionsAndCollateConnectionConfigurationRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename $GLOBALS[\'TYPO3_CONF_VARS\'][\'DB\'][\'Connections\'][CONNECTION_NAME][\'tableoptions\'] to `defaultTableOptions` and its inner `collate` key to `collation`. This applies to full array definitions and direct path assignments.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
return [
    'DB' => [
        'Connections' => [
            'Default' => [
                'tableoptions' => [
                    'collate' => 'utf8mb4_unicode_ci',
                ],
            ],
        ],
    ],
];
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
return [
    'DB' => [
        'Connections' => [
            'Default' => [
                'defaultTableOptions' => [
                    'collation' => 'utf8mb4_unicode_ci',
                ],
            ],
        ],
    ],
];
CODE_SAMPLE
                ),
                new CodeSample(
                    <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['tableoptions']['collate'] = 'utf8mb4_unicode_ci';
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['defaultTableOptions']['collation'] = 'utf8mb4_unicode_ci';
CODE_SAMPLE
                ),
                new CodeSample(
                    <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['tableoptions'] = [
    'collate' => 'latin1_swedish_ci',
    'engine' => 'InnoDB',
];
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['defaultTableOptions'] = [
    'collation' => 'latin1_swedish_ci',
    'engine' => 'InnoDB',
];
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Array_::class, Assign::class];
    }

    /**
     * @param Array_|Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Array_) {
            $hasChanged = $this->processTypo3ConfVarsArray($node);
            return $hasChanged ? $node : null;
        }

        if ($node instanceof Assign) {
            return $this->processAssignment($node);
        }

        return null;
    }

    private function processTypo3ConfVarsArray(Array_ $arrayNode): bool
    {
        $dbItem = $this->findArrayItemByKey($arrayNode, 'DB');
        if (! $dbItem || ! $dbItem->value instanceof Array_) {
            return false;
        }

        $connectionsItem = $this->findArrayItemByKey($dbItem->value, 'Connections');
        if (! $connectionsItem || ! $connectionsItem->value instanceof Array_) {
            return false;
        }

        $connectionsArray = $connectionsItem->value;
        $hasChanged = false;

        foreach ($connectionsArray->items as $connectionItemNode) {
            if (! $connectionItemNode instanceof ArrayItem || ! $connectionItemNode->value instanceof Array_) {
                continue;
            }

            $connectionConfigArray = $connectionItemNode->value;

            $tableOptionsItem = $this->findArrayItemByKey($connectionConfigArray, 'tableoptions');
            if ($tableOptionsItem instanceof ArrayItem && $tableOptionsItem->key instanceof String_ && $tableOptionsItem->key->value === 'tableoptions') {
                $tableOptionsItem->key = new String_('defaultTableOptions');
                $hasChanged = true;

                if ($tableOptionsItem->value instanceof Array_) {
                    $this->processCollateInTableOptionsArray($tableOptionsItem->value);
                }
            }
        }

        return $hasChanged;
    }

    private function processAssignment(Assign $assign): ?Node
    {
        if (! $assign->var instanceof ArrayDimFetch) {
            return null;
        }

        $hasChanged = false;

        // Try to refactor path like $...['tableoptions']['collate']
        $refactoredCollatePath = $this->refactorTableOptionsCollatePath($assign->var);
        if ($refactoredCollatePath instanceof ArrayDimFetch) {
            // $assign->var was modified in place by refactorTableOptionsCollatePath
            $hasChanged = true;
        } else {
            // If not the collate path, try to refactor path like $...['tableoptions']
            $refactoredTableOptionsPath = $this->refactorTableOptionsPath($assign->var);
            if ($refactoredTableOptionsPath instanceof ArrayDimFetch) {
                // $assign->var was modified in place by refactorTableOptionsPath
                $hasChanged = true;
                if ($assign->expr instanceof Array_) {
                    $this->processCollateInTableOptionsArray($assign->expr);
                }
            }
        }

        return $hasChanged ? $assign : null;
    }

    /**
     * Modifies $collateDimFetch in place if it matches the pattern and returns it.
     * Otherwise, returns null.
     */
    private function refactorTableOptionsCollatePath(ArrayDimFetch $collateDimFetch): ?ArrayDimFetch
    {
        if (! ($collateDimFetch->dim instanceof String_ && $collateDimFetch->dim->value === 'collate')) {
            return null;
        }

        if (! $collateDimFetch->var instanceof ArrayDimFetch) {
            return null;
        }

        $tableOptionsDimFetch = $collateDimFetch->var;

        if (! ($tableOptionsDimFetch->dim instanceof String_ && $tableOptionsDimFetch->dim->value === 'tableoptions')) {
            return null;
        }

        if (! $this->isBasePathOfTyposcriptConfigurationArray($tableOptionsDimFetch->var)) {
            return null;
        }

        // Perform modifications in place
        $tableOptionsDimFetch->dim = new String_('defaultTableOptions');
        $collateDimFetch->dim = new String_('collation');

        return $collateDimFetch;
    }

    /**
     * Modifies $tableOptionsDimFetch in place if it matches the pattern and returns it.
     * Otherwise, returns null.
     */
    private function refactorTableOptionsPath(ArrayDimFetch $tableOptionsDimFetch): ?ArrayDimFetch
    {
        if (! ($tableOptionsDimFetch->dim instanceof String_ && $tableOptionsDimFetch->dim->value === 'tableoptions')) {
            return null;
        }

        if (! $this->isBasePathOfTyposcriptConfigurationArray($tableOptionsDimFetch->var)) {
            return null;
        }

        // Perform modification in place
        $tableOptionsDimFetch->dim = new String_('defaultTableOptions');
        return $tableOptionsDimFetch;
    }

    /**
     * Checks if the given node represents $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections'][CONNECTION_NAME]
     * CONNECTION_NAME can be a literal string or a variable.
     */
    private function isBasePathOfTyposcriptConfigurationArray(Node $node): bool
    {
        // $node represents the part up to ...['Connections'][$connectionName]

        // Check for [$connectionName]
        if (! $node instanceof ArrayDimFetch) {
            return false;
        }

        // $node->dim is the $connectionName. It can be String_ or Variable. We just check it's not null.
        if (! $node->dim instanceof Expr) {
            return false;
        }

        $connectionsNode = $node->var;

        // Check for ['Connections']
        if (! ($connectionsNode instanceof ArrayDimFetch
            && $connectionsNode->dim instanceof String_
            && $connectionsNode->dim->value === 'Connections')
        ) {
            return false;
        }

        $dbNode = $connectionsNode->var;

        // Check for ['DB']
        if (! ($dbNode instanceof ArrayDimFetch
            && $dbNode->dim instanceof String_
            && $dbNode->dim->value === 'DB')
        ) {
            return false;
        }

        $typo3ConfVarsNode = $dbNode->var;

        // Check for ['TYPO3_CONF_VARS']
        if (! ($typo3ConfVarsNode instanceof ArrayDimFetch
            && $typo3ConfVarsNode->dim instanceof String_
            && $typo3ConfVarsNode->dim->value === 'TYPO3_CONF_VARS')
        ) {
            return false;
        }

        $globalsNode = $typo3ConfVarsNode->var;

        // Check for $GLOBALS
        return $globalsNode instanceof Variable && $globalsNode->name === 'GLOBALS';
    }

    private function findArrayItemByKey(Array_ $array, string $keyName): ?ArrayItem
    {
        foreach ($array->items as $item) {
            if (! $item instanceof ArrayItem) {
                continue;
            }

            if ($item->key instanceof String_ && $item->key->value === $keyName) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Processes an array that is the value of 'tableoptions' (or 'defaultTableOptions'),
     * changing 'collate' key to 'collation'.
     */
    private function processCollateInTableOptionsArray(Array_ $tableOptionsValueArray): void
    {
        $collateItem = $this->findArrayItemByKey($tableOptionsValueArray, 'collate');
        if ($collateItem instanceof ArrayItem
            && $collateItem->key instanceof String_
            && $collateItem->key->value === 'collate'
        ) {
            $collateItem->key = new String_('collation');
        }
    }
}
