<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v3\MigrateGeneralUtilityGPRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    public function myMethod(): void
    {
        $value = GeneralUtility::_GP('tx_scheduler');

        $anotherValue = GeneralUtility::_GP(null);
        $anotherValue2 = GeneralUtility::_GP();
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v3\MigrateGeneralUtilityGPRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    public function myMethod(): void
    {
        $value = $GLOBALS['TYPO3_REQUEST']->getParsedBody()['tx_scheduler'] ?? $GLOBALS['TYPO3_REQUEST']->getQueryParams()['tx_scheduler'] ?? null;

        $anotherValue = GeneralUtility::_GP(null);
        $anotherValue2 = GeneralUtility::_GP();
    }
}

?>
