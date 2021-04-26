<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\RemoveMethodsFromEidUtilityAndTsfeRector\Fixture;

use TYPO3\CMS\Frontend\Utility\EidUtility;

EidUtility::initExtensionTCA('foo');
EidUtility::initFeUser();
EidUtility::initLanguage();
EidUtility::initTCA();

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\RemoveMethodsFromEidUtilityAndTsfeRector\Fixture;

use TYPO3\CMS\Frontend\Utility\EidUtility;

?>
