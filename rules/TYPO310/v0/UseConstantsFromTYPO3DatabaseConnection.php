<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO310\v0;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/8.1/Feature-75454-DoctrineDBALForDatabaseConnections.html
 * The rules for TYPO3 prior v10 have been deleted; that is why these are added here.
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v0\UseConstantsFromTYPO3DatabaseConnection\UseConstantsFromTYPO3DatabaseConnectionTest
 */
final class UseConstantsFromTYPO3DatabaseConnection extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use strict types in Extbase ActionController', [new CodeSample(
            <<<'CODE_SAMPLE'
$queryBuilder = $this->connectionPool->getQueryBuilderForTable('table');
$result = $queryBuilder
    ->select('uid')
    ->from('table')
    ->where(
        $queryBuilder->expr()->eq('bodytext', $queryBuilder->createNamedParameter('lorem', \PDO::PARAM_STR)),
        $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter(42, \PDO::PARAM_INT)),
        $queryBuilder->expr()->eq('available', $queryBuilder->createNamedParameter(true, \PDO::PARAM_BOOL)),
        $queryBuilder->expr()->eq('foo', $queryBuilder->createNamedParameter(true, \PDO::PARAM_NULL))
    )
    ->executeQuery();
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Database\Connection;

$queryBuilder = $this->connectionPool->getQueryBuilderForTable('table');
$result = $queryBuilder
    ->select('uid')
    ->from('table')
    ->where(
        $queryBuilder->expr()->eq('bodytext', $queryBuilder->createNamedParameter('lorem', Connection::PARAM_STR)),
        $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter(42, Connection::PARAM_INT)),
        $queryBuilder->expr()->eq('available', $queryBuilder->createNamedParameter(true, Connection::PARAM_BOOL)),
        $queryBuilder->expr()->eq('foo', $queryBuilder->createNamedParameter(true, Connection::PARAM_NULL))
    )
    ->executeQuery();
CODE_SAMPLE
        )]);
    }

    public function getNodeTypes(): array
    {
        return [];
    }

    public function refactor(Node $node)
    {
        return null;
    }
}
