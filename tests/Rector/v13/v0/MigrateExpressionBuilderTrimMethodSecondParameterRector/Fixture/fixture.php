<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateExpressionBuilderTrimMethodSecondParameterRector\Fixture;

use Doctrine\DBAL\Platforms\TrimMode;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$queryBuilder = GeneralUtility::makeInstance(Connection::class)->createQueryBuilder();
$queryBuilder->expr()
    ->comparison(
        $queryBuilder->expr()
            ->trim('string', 0),
        ExpressionBuilder::EQ,
        $queryBuilder->createNamedParameter('', Connection::PARAM_STR)
    );

$queryBuilder->expr()
    ->comparison(
        $queryBuilder->expr()
            ->trim('string', 1),
        ExpressionBuilder::EQ,
        $queryBuilder->createNamedParameter('', Connection::PARAM_STR)
    );

$queryBuilder->expr()
    ->comparison(
        $queryBuilder->expr()
            ->trim('string', 2),
        ExpressionBuilder::EQ,
        $queryBuilder->createNamedParameter('', Connection::PARAM_STR)
    );

$queryBuilder->expr()
    ->comparison(
        $queryBuilder->expr()
            ->trim('string', 3),
        ExpressionBuilder::EQ,
        $queryBuilder->createNamedParameter('', Connection::PARAM_STR)
    );

$queryBuilder->expr()
    ->comparison(
        $queryBuilder->expr()
            ->trim('string', 5),
        ExpressionBuilder::EQ,
        $queryBuilder->createNamedParameter('', Connection::PARAM_STR)
    );

$queryBuilder->expr()
    ->comparison(
        $queryBuilder->expr()
            ->trim('string', TrimMode::LEADING),
        ExpressionBuilder::EQ,
        $queryBuilder->createNamedParameter('', Connection::PARAM_STR)
    );
