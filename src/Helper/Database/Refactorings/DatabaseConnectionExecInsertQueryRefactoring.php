<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper\Database\Refactorings;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use Rector\Core\PhpParser\Node\NodeFactory;
use Ssch\TYPO3Rector\Contract\Helper\Database\Refactorings\DatabaseConnectionToDbalRefactoring;

final class DatabaseConnectionExecInsertQueryRefactoring implements DatabaseConnectionToDbalRefactoring
{
    /**
     * @readonly
     */
    private ConnectionCallFactory $connectionCallFactory;

    /**
     * @readonly
     */
    private NodeFactory $nodeFactory;

    public function __construct(ConnectionCallFactory $connectionCallFactory, NodeFactory $nodeFactory)
    {
        $this->connectionCallFactory = $connectionCallFactory;
        $this->nodeFactory = $nodeFactory;
    }

    /**
     * @return Expr[]
     */
    public function refactor(MethodCall $oldMethodCall): array
    {
        $tableArgument = array_shift($oldMethodCall->args);
        $dataArgument = array_shift($oldMethodCall->args);

        if (! $tableArgument instanceof Arg || ! $dataArgument instanceof Arg) {
            return [];
        }

        $connectionAssignment = $this->connectionCallFactory->createConnectionCall($tableArgument);

        $connectionInsertCall = $this->nodeFactory->createMethodCall(
            new Variable('connection'),
            'insert',
            [$tableArgument->value, $dataArgument->value]
        );

        return [$connectionAssignment, $connectionInsertCall];
    }

    public function canHandle(string $methodName): bool
    {
        return 'exec_INSERTquery' === $methodName;
    }
}
