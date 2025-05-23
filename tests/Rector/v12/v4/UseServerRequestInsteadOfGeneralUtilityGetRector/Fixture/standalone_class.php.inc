<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\UseServerRequestInsteadOfGeneralUtilityGetRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    public function myMethod(): void
    {
        $value = GeneralUtility::_GET('tx_scheduler');

        $anotherValue = GeneralUtility::_GET(null);
        $anotherValue2 = GeneralUtility::_GET();
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\UseServerRequestInsteadOfGeneralUtilityGetRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    public function myMethod(): void
    {
        $value = $GLOBALS['TYPO3_REQUEST']->getQueryParams()['tx_scheduler'] ?? null;

        $anotherValue = $GLOBALS['TYPO3_REQUEST']->getQueryParams();
        $anotherValue2 = $GLOBALS['TYPO3_REQUEST']->getQueryParams();
    }
}

?>
