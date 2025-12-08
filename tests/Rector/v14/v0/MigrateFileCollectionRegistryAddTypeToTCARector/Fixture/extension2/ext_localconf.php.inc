<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateFileCollectionRegistryAddTypeToTCARector\Fixture;

use TYPO3\CMS\Core\Resource\Collection\FileCollectionRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$fileCollectionRegistry = GeneralUtility::makeInstance(FileCollectionRegistry::class);
$fileCollectionRegistry->addTypeToTCA(
    'myothertype',
    'My Other Collection Type',
    'other_description, my_other_field',
    ['my_other_field' => ['config' => ['type' => 'input']]]
);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateFileCollectionRegistryAddTypeToTCARector\Fixture;

use TYPO3\CMS\Core\Resource\Collection\FileCollectionRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$fileCollectionRegistry = GeneralUtility::makeInstance(FileCollectionRegistry::class);
?>
