<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper\Database\Refactorings;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;

final class DatabaseConnectionExecInsertQueryRefactoring implements DatabaseConnectionToDbalRefactoring
{
    use ConnectionCallTrait;

    public function refactor(MethodCall $oldNode): array
    {
        $tableArgument = array_shift($oldNode->args);
        $dataArgument = array_shift($oldNode->args);

        if (null === $tableArgument || null === $dataArgument) {
            return [];
        }

        $connectionAssignment = $this->createConnectionCall($tableArgument);

        $connectionInsertCall = $this->createMethodCall(
            new Variable('connection'), 'insert', [$tableArgument->value, $dataArgument->value]
        );

        return [$connectionAssignment, $connectionInsertCall];
    }

    public function canHandle(string $methodName): bool
    {
        return 'exec_INSERTquery' === $methodName;
    }
}
