<?php

final class ClassWithDataHandlerRmCommaMethod
{
    public function method(): void
    {
        $inList = '1,2,3,';
        $dataHandler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
        $inList = $dataHandler->rmComma(trim($inList));
    }
}

?>
-----
<?php

final class ClassWithDataHandlerRmCommaMethod
{
    public function method(): void
    {
        $inList = '1,2,3,';
        $dataHandler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
        $inList = rtrim($inList, ',');
    }
}

?>
