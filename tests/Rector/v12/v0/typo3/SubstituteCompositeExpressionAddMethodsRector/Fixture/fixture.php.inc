<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\SubstituteCompositeExpressionAddMethodsRector\Fixture;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('foo');

$compositeExpression = CompositeExpression::or();

$compositeExpression->add(
    $queryBuilder->expr()->eq(
        'field',
        $queryBuilder->createNamedParameter('foo')
    )
);

$compositeExpression->addMultiple(
    [
        $queryBuilder->expr()->eq(
            'field',
            $queryBuilder->createNamedParameter('bar')
        ),
        $queryBuilder->expr()->eq(
            'field',
            $queryBuilder->createNamedParameter('baz')
        ),
    ]
);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\SubstituteCompositeExpressionAddMethodsRector\Fixture;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('foo');

$compositeExpression = CompositeExpression::or();

$compositeExpression = $compositeExpression->with($queryBuilder->expr()->eq(
    'field',
    $queryBuilder->createNamedParameter('foo')
));

$compositeExpression = $compositeExpression->with(...[
    $queryBuilder->expr()->eq(
        'field',
        $queryBuilder->createNamedParameter('bar')
    ),
    $queryBuilder->expr()->eq(
        'field',
        $queryBuilder->createNamedParameter('baz')
    ),
]);

?>
