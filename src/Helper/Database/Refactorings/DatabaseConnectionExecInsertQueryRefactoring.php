<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper\Database\Refactorings;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use Rector\Core\Rector\AbstractRector\NodeFactoryTrait;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class DatabaseConnectionExecInsertQueryRefactoring implements DatabaseConnectionToDbalRefactoring
{
    use NodeFactoryTrait;

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

    private function createConnectionCall(Arg $firstArgument): Assign
    {
        $connection = $this->createMethodCall($this->createStaticCall(GeneralUtility::class, 'makeInstance', [
            $this->createClassConstantReference(ConnectionPool::class),
        ]), 'getConnectionForTable', [$this->createArg($firstArgument->value)]);

        return new Assign(new Variable('connection'), $connection);
    }
}
