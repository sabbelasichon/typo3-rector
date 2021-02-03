<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper\Database\Refactorings;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;

final class DatabaseConnectionExecTruncateTableRefactoring implements DatabaseConnectionToDbalRefactoring
{
    use ConnectionCallTrait;

    public function refactor(MethodCall $oldNode): array
    {
        $tableArgument = array_shift($oldNode->args);

        if (null === $tableArgument) {
            return [];
        }

        $connectionAssignment = $this->createConnectionCall($tableArgument);

        $connectionInsertCall = $this->nodeFactory->createMethodCall(
            new Variable('connection'), 'truncate', [$tableArgument->value]
        );

        return [$connectionAssignment, $connectionInsertCall];
    }

    public function canHandle(string $methodName): bool
    {
        return 'exec_TRUNCATEquery' === $methodName;
    }
}
