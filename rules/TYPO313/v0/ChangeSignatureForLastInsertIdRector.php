<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102875-ChangedConnectionMethodSignaturesAndBehaviour.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\ChangeSignatureForLastInsertIdRector\ChangeSignatureForLastInsertIdRectorTest
 */
final class ChangeSignatureForLastInsertIdRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove table argument from lastInsertID() call', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$connection = GeneralUtility::makeInstance(ConnectionPool::class)
    ->getConnectionForTable('tx_myextension_mytable');

$uid = $connection->lastInsertId('tx_myextension_mytable');
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$connection = GeneralUtility::makeInstance(ConnectionPool::class)
    ->getConnectionForTable('tx_myextension_mytable');

$uid = $connection->lastInsertId();
CODE_SAMPLE
        )]);
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node->name, 'lastInsertId')) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Database\Connection')
        )) {
            return null;
        }

        if (count($node->args) === 0) {
            return null;
        }

        $node->args = [];
        return $node;
    }
}
