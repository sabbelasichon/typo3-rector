<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\UseServerRequestInsteadOfGeneralUtilityGetRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    private array $myArray = [];
    private string $myString = '';

    public function myMethod(): void
    {
        $this->myArray = GeneralUtility::_GET('tx_scheduler') ?? [];
        $this->myString = GeneralUtility::_GET('tx_scheduler') ?? '';
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\UseServerRequestInsteadOfGeneralUtilityGetRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    private array $myArray = [];
    private string $myString = '';

    public function myMethod(): void
    {
        $this->myArray = $GLOBALS['TYPO3_REQUEST']->getQueryParams()['tx_scheduler'] ?? [];
        $this->myString = $GLOBALS['TYPO3_REQUEST']->getQueryParams()['tx_scheduler'] ?? '';
    }
}

?>
