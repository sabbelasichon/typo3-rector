<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v3\typo3\MigrateMagicRepositoryMethodsRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ExampleRepository;
use TYPO3\CMS\Extbase\Persistence\Repository;

$exampleRepo = GeneralUtility::makeInstance(Repository::class);
$exampleRepo->findByFoo('bar');
$exampleRepo->findByFooBar('bar');
$bar = 'bar';
$exampleRepo->findByFoo($bar);
$exampleRepo->findOneByFoo('bar');
$exampleRepo->countByFoo('bar');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v3\typo3\MigrateMagicRepositoryMethodsRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ExampleRepository;
use TYPO3\CMS\Extbase\Persistence\Repository;

$exampleRepo = GeneralUtility::makeInstance(Repository::class);
$exampleRepo->findBy(['foo' => 'bar']);
$exampleRepo->findBy(['fooBar' => 'bar']);
$bar = 'bar';
$exampleRepo->findBy(['foo' => $bar]);
$exampleRepo->findOneBy(['foo' => 'bar']);
$exampleRepo->count(['foo' => 'bar']);

?>
