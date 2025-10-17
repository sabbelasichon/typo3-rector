<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v3\MigrateExtensionManagementUtilityAddTcaSelectItemRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addTcaSelectItem('table', 'field', [
    'My Content Element',
    'my_content_element',
    'my-icon-identifier',
    'group1',
    'My Description',
]);

ExtensionManagementUtility::addTcaSelectItem('table', 'field', [
    'My Content Element',
    'my_content_element',
    'my-icon-identifier',
]);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v3\MigrateExtensionManagementUtilityAddTcaSelectItemRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addTcaSelectItem('table', 'field', [
    'label' => 'My Content Element',
    'value' => 'my_content_element',
    'icon' => 'my-icon-identifier',
    'group' => 'group1',
    'description' => 'My Description',
]);

ExtensionManagementUtility::addTcaSelectItem('table', 'field', [
    'label' => 'My Content Element',
    'value' => 'my_content_element',
    'icon' => 'my-icon-identifier',
]);

?>
