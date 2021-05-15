<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper\Database\Refactorings;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use Rector\Core\PhpParser\Node\NodeFactory;
use Ssch\TYPO3Rector\Contract\Helper\Database\Refactorings\DatabaseConnectionToDbalRefactoring;

final class DatabaseConnectionExecInsertQueryRefactoring implements DatabaseConnectionToDbalRefactoring
{
    public function __construct(private ConnectionCallFactory $connectionCallFactory, private NodeFactory $nodeFactory)
    {
    }

    /**
     * @return Expr[]
     */
    public function refactor(MethodCall $oldNode): array
    {
        $tableArgument = array_shift($oldNode->args);
        $dataArgument = array_shift($oldNode->args);

        if (null === $tableArgument || null === $dataArgument) {
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
