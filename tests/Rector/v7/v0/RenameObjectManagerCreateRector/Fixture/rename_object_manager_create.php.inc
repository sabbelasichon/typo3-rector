<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v7\v0\RenameObjectManagerCreateRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function someMethod()
    {
        $object = $this->objectManager->create(ActionController::class);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v7\v0\RenameObjectManagerCreateRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function someMethod()
    {
        $object = $this->objectManager->get(ActionController::class);
    }
}

?>
