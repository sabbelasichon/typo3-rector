<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\UseExtensionKeyInLocalizationUtilityRector\Fixture;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

$key1 = LocalizationUtility::translate('key1', 'extension_key');
$key2 = LocalizationUtility::translate('key2', 'ExtensionKey');
$key3 = LocalizationUtility::translate('key3', 'my_extension_key');
$key4 = LocalizationUtility::translate('key4');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\UseExtensionKeyInLocalizationUtilityRector\Fixture;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

$key1 = LocalizationUtility::translate('key1', 'ExtensionKey');
$key2 = LocalizationUtility::translate('key2', 'ExtensionKey');
$key3 = LocalizationUtility::translate('key3', 'MyExtensionKey');
$key4 = LocalizationUtility::translate('key4');

?>
