<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ContentObjectRegistrationViaServiceConfigurationRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ContentObjectRegistrationViaServiceConfigurationRector\Source\MultiValue;
use Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ContentObjectRegistrationViaServiceConfigurationRector\Source\SingleValue;

$GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'][Multivalue::CONTENT_OBJECT_NAME] = Multivalue::class;
$GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'][SingleValue::CONTENT_OBJECT_NAME] = SingleValue::class;

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ContentObjectRegistrationViaServiceConfigurationRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ContentObjectRegistrationViaServiceConfigurationRector\Source\MultiValue;
use Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ContentObjectRegistrationViaServiceConfigurationRector\Source\SingleValue;

?>
