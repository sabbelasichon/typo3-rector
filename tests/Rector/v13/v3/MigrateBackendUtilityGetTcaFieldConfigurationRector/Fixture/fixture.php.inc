<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateBackendUtilityGetTcaFieldConfigurationRector\Fixture;

use TYPO3\CMS\Backend\Utility\BackendUtility;

$fieldConfig = BackendUtility::getTcaFieldConfiguration('my_table', 'my_field');

$tableName = 'my_table';
$fieldName = 'my_field';
$fieldConfigVariable = BackendUtility::getTcaFieldConfiguration($tableName, $fieldName);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateBackendUtilityGetTcaFieldConfigurationRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Schema\TcaSchemaFactory;
use TYPO3\CMS\Backend\Utility\BackendUtility;

$fieldConfig = GeneralUtility::makeInstance(TcaSchemaFactory::class)->get('my_table')->getField('my_field')->getConfiguration();

$tableName = 'my_table';
$fieldName = 'my_field';
$fieldConfigVariable = GeneralUtility::makeInstance(TcaSchemaFactory::class)->get($tableName)->getField($fieldName)->getConfiguration();
?>
