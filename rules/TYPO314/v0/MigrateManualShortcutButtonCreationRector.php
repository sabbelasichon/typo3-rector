<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-108008-ManualShortcutButtonCreation.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateManualShortcutButtonCreationRector\MigrateManualShortcutButtonCreationRectorTest
 */
final class MigrateManualShortcutButtonCreationRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate manual shortcut button creation', [new CodeSample(
            <<<'CODE_SAMPLE'
$shortcutButton = $this->componentFactory->createShortcutButton()
    ->setRouteIdentifier('my_module')
    ->setDisplayName('My Module')
    ->setArguments(['id' => $pageId]);
$view->addButtonToButtonBar($shortcutButton);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$view->getDocHeaderComponent()->setShortcutContext(
    routeIdentifier: 'my_module',
    displayName: 'My Module',
    arguments: ['id' => $pageId]
);
CODE_SAMPLE
        )]);
    }

    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        $stmts = $node->stmts;
        if ($stmts === null) {
            return null;
        }

        $hasChanged = false;

        foreach ($stmts as $key => $stmt) {
            // We are looking for the assignment: $shortcutButton = ...
            if (! $stmt instanceof Expression || ! $stmt->expr instanceof Assign) {
                continue;
            }

            $assign = $stmt->expr;
            if (! $assign->var instanceof Variable) {
                continue;
            }

            // Check if the right side is the componentFactory->createShortcutButton chain
            if (! $this->isShortcutButtonChain($assign->expr)) {
                continue;
            }

            // We found the definition. Now we need to find the usage: $view->addButtonToButtonBar($shortcutButton)
            // We scan the subsequent statements in the same scope.
            $usageFound = false;
            $viewVariable = null;
            $usageStmtKey = null;

            for ($i = $key + 1, $iMax = count($stmts); $i < $iMax; $i++) {
                $nextStmt = $stmts[$i];
                if (! $nextStmt instanceof Expression) {
                    continue;
                }

                if (! $nextStmt->expr instanceof MethodCall) {
                    continue;
                }

                $methodCall = $nextStmt->expr;
                if (! $this->isName($methodCall->name, 'addButtonToButtonBar')) {
                    continue;
                }

                // Check if the argument matches our shortcut button variable
                if (isset($methodCall->args[0])
                    && $this->nodeComparator->areNodesEqual($methodCall->args[0]->value, $assign->var)
                ) {
                    $usageFound = true;
                    $viewVariable = $methodCall->var;
                    $usageStmtKey = $i;
                    break;
                }
            }

            if ($usageFound && $viewVariable) {
                // Extract arguments from the original chain
                $args = $this->extractArgsFromChain($assign->expr);

                // Create the new method call: $view->getDocHeaderComponent()
                $getDocHeader = $this->nodeFactory->createMethodCall($viewVariable, 'getDocHeaderComponent');

                // Prepare arguments for setShortcutContext.
                // We map them positionally for PHP 7.4 compatibility.
                // Order: routeIdentifier, displayName, arguments
                $newArgs = [
                    $args['routeIdentifier'] ?? $this->nodeFactory->createNull(),
                    $args['displayName'] ?? $this->nodeFactory->createNull(),
                    $args['arguments'] ?? $this->nodeFactory->createArray([]),
                ];

                $setContextCall = $this->nodeFactory->createMethodCall($getDocHeader, 'setShortcutContext', $newArgs);

                // Replace the assignment statement with the new method call
                $stmt->expr = $setContextCall;

                // Remove the addButtonToButtonBar call statement
                unset($stmts[$usageStmtKey]);

                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            // Re-index stmts to prevent issues with array gaps
            $node->stmts = array_values($stmts);
            return $node;
        }

        return null;
    }

    private function isShortcutButtonChain(Node $node): bool
    {
        // Traverse down the method call chain to find 'createShortcutButton'
        $current = $node;
        while ($current instanceof MethodCall) {
            if ($this->isName($current->name, 'createShortcutButton')) {
                return true;
            }

            $current = $current->var;
        }

        return false;
    }

    /**
     * @return array<string, mixed>
     */
    private function extractArgsFromChain(Node $node): array
    {
        $args = [
            'routeIdentifier' => null,
            'displayName' => null,
            'arguments' => null,
        ];

        $current = $node;
        while ($current instanceof MethodCall) {
            $methodName = $this->getName($current->name);

            if ($methodName === 'setRouteIdentifier' && isset($current->args[0])) {
                $args['routeIdentifier'] = $current->args[0]->value;
            } elseif ($methodName === 'setDisplayName' && isset($current->args[0])) {
                $args['displayName'] = $current->args[0]->value;
            } elseif ($methodName === 'setArguments' && isset($current->args[0])) {
                $args['arguments'] = $current->args[0]->value;
            }

            $current = $current->var;
        }

        return $args;
    }
}
