<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper\Database\Refactorings;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
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

    public function refactor(MethodCall $oldMethodCall): ?Node
    {
        $tableArgument = array_shift($oldMethodCall->args);
        $dataArgument = array_shift($oldMethodCall->args);

        if (! $tableArgument instanceof Arg || ! $dataArgument instanceof Arg) {
            return null;
        }

        return $this->nodeFactory->createMethodCall(
            $this->connectionCallFactory->createConnectionCall($tableArgument),
            'insert',
            [$tableArgument->value, $dataArgument->value]
        );
    }

    public function canHandle(string $methodName): bool
    {
        return $methodName === 'exec_INSERTquery';
    }
}
