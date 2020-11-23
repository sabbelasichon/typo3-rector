<?php

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DataHandlerVariousMethodsAndMethodArguments
{
    public function method(): void
    {
        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dest = $dataHandler->destPathFromUploadFolder('uploadFolder');
        $dataHandler->extFileFunctions('table', 'field', 'theField', 'deleteAll');
    }
}
?>
-----
<?php

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DataHandlerVariousMethodsAndMethodArguments
{
    public function method(): void
    {
        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dest = PATH_site . 'uploadFolder';
        $dataHandler->extFileFunctions('table', 'field', 'theField');
    }
}
?>
