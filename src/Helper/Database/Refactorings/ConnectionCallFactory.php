<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper\Database\Refactorings;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\PhpParser\Node\NodeFactory;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ConnectionCallFactory
{
    /**
     * @readonly
     */
    private NodeFactory $nodeFactory;

    public function __construct(NodeFactory $nodeFactory)
    {
        $this->nodeFactory = $nodeFactory;
    }

    public function createConnectionCall(Arg $firstArgument): MethodCall
    {
        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall(GeneralUtility::class, 'makeInstance', [
                $this->nodeFactory->createClassConstReference(ConnectionPool::class),
            ]),
            'getConnectionForTable',
            [$this->nodeFactory->createArg($firstArgument->value)]
        );
    }
}
