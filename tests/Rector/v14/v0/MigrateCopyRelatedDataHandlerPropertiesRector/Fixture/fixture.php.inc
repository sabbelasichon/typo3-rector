<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateCopyRelatedDataHandlerPropertiesRector\Fixture;

use TYPO3\CMS\Core\DataHandling\DataHandler;

class MyClass
{
    public function myMethod(DataHandler $dataHandler)
    {
        $neverHideAtCopy = $dataHandler->neverHideAtCopy;
        $copyTree = $dataHandler->copyTree;
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateCopyRelatedDataHandlerPropertiesRector\Fixture;

use TYPO3\CMS\Core\DataHandling\DataHandler;

class MyClass
{
    public function myMethod(DataHandler $dataHandler)
    {
        $neverHideAtCopy = $dataHandler->BE_USER->uc['neverHideAtCopy'];
        $copyTree = $dataHandler->BE_USER->uc['copyLevels'];
    }
}

?>
