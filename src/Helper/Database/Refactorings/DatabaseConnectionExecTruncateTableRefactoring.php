<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper\Database\Refactorings;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use Rector\Core\PhpParser\Node\NodeFactory;
use Ssch\TYPO3Rector\Contract\Helper\Database\Refactorings\DatabaseConnectionToDbalRefactoring;

final class DatabaseConnectionExecTruncateTableRefactoring implements DatabaseConnectionToDbalRefactoring
{
    /**
     * @var ConnectionCallFactory
     */
    private $connectionCallFactory;

    /**
     * @var NodeFactory
     */
    private $nodeFactory;

    public function __construct(ConnectionCallFactory $connectionCallFactory, NodeFactory $nodeFactory)
    {
        $this->connectionCallFactory = $connectionCallFactory;
        $this->nodeFactory = $nodeFactory;
    }

    public function refactor(MethodCall $oldNode): array
    {
        $tableArgument = array_shift($oldNode->args);

        if (null === $tableArgument) {
            return [];
        }

        $connectionAssignment = $this->connectionCallFactory->createConnectionCall($tableArgument);

        $connectionInsertCall = $this->nodeFactory->createMethodCall(
            new Variable('connection'),
            'truncate',
            [$tableArgument->value]
        );

        return [$connectionAssignment, $connectionInsertCall];
    }

    public function canHandle(string $methodName): bool
    {
        return 'exec_TRUNCATEquery' === $methodName;
    }
}
