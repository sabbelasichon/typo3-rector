<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper\Database\Refactorings;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use Rector\Core\Rector\AbstractRector\NodeFactoryTrait;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

trait ConnectionCallTrait
{
    use NodeFactoryTrait;

    private function createConnectionCall(Arg $firstArgument): Assign
    {
        $connection = $this->createMethodCall($this->createStaticCall(GeneralUtility::class, 'makeInstance', [
            $this->createClassConstantReference(ConnectionPool::class),
        ]), 'getConnectionForTable', [$this->createArg($firstArgument->value)]);

        return new Assign(new Variable('connection'), $connection);
    }
}
