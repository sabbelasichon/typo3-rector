<?php

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ClassWithDataHandlerRmCommaMethod
{
    public function method(): void
    {
        $inList = '1,2,3,';
        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $inList = $dataHandler->rmComma(trim($inList));
    }
}

?>
-----
<?php

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ClassWithDataHandlerRmCommaMethod
{
    public function method(): void
    {
        $inList = '1,2,3,';
        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $inList = rtrim(trim($inList), ',');
    }
}

?>
