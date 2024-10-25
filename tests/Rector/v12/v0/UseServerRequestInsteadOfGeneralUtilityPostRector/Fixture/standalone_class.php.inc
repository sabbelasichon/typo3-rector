<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\UseServerRequestInsteadOfGeneralUtilityPostRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    public function myMethod(): void
    {
        $value = GeneralUtility::_POST('tx_scheduler');

        $anotherValue = GeneralUtility::_POST(null);
        $anotherValue2 = GeneralUtility::_POST();
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\UseServerRequestInsteadOfGeneralUtilityPostRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    public function myMethod(): void
    {
        $value = $GLOBALS['TYPO3_REQUEST']->getParsedBody()['tx_scheduler'] ?? null;

        $anotherValue = $GLOBALS['TYPO3_REQUEST']->getParsedBody();
        $anotherValue2 = $GLOBALS['TYPO3_REQUEST']->getParsedBody();
    }
}

?>
