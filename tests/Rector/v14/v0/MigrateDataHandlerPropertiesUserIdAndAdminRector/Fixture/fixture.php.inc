<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateDataHandlerPropertiesUserIdAndAdminRector\Fixture;

use TYPO3\CMS\Core\DataHandling\DataHandler;

class MyClass
{
    public function foo(DataHandler $dataHandler)
    {
        $userId = $dataHandler->userid;

        if ($dataHandler->admin) {
            // do something
        }
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateDataHandlerPropertiesUserIdAndAdminRector\Fixture;

use TYPO3\CMS\Core\DataHandling\DataHandler;

class MyClass
{
    public function foo(DataHandler $dataHandler)
    {
        $userId = $dataHandler->BE_USER->getUserId();

        if ($dataHandler->BE_USER->isAdmin()) {
            // do something
        }
    }
}

?>
