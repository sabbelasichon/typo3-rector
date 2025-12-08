<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateFileCollectionRegistryAddTypeToTCARector\Fixture;

use TYPO3\CMS\Core\Resource\Collection\FileCollectionRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$fileCollectionRegistry = GeneralUtility::makeInstance(FileCollectionRegistry::class);
$fileCollectionRegistry->addTypeToTCA(
    'mytype',
    'My Collection Type',
    'description, my_field',
    ['my_field' => ['config' => ['type' => 'input']]]
);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateFileCollectionRegistryAddTypeToTCARector\Fixture;

use TYPO3\CMS\Core\Resource\Collection\FileCollectionRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$fileCollectionRegistry = GeneralUtility::makeInstance(FileCollectionRegistry::class);
?>
