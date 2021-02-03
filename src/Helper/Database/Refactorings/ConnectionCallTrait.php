<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper\Database\Refactorings;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use Rector\Core\PhpParser\Node\NodeFactory;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

trait ConnectionCallTrait
{
    /**
     * @var NodeFactory
     */
    protected $nodeFactory;

    /**
     * @required
     */
    public function autowireNodeFactoryTrait(NodeFactory $nodeFactory): void
    {
        $this->nodeFactory = $nodeFactory;
    }

    private function createConnectionCall(Arg $firstArgument): Assign
    {
        $connection = $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall(GeneralUtility::class, 'makeInstance', [
                $this->nodeFactory->createClassConstReference(ConnectionPool::class),
            ]),
            'getConnectionForTable',
            [$this->nodeFactory->createArg($firstArgument->value)]
        );

        return new Assign(new Variable('connection'), $connection);
    }
}
