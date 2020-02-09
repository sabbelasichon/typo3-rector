<?php

use TYPO3\CMS\Backend\Controller\SimpleDataHandlerController;

final class MySimpleDataHandlerController extends SimpleDataHandlerController
{
    public function myMethod()
    {
        $pErr = $this->prErr;
        $this->prErr = true;
        $this->uPT = true;
    }
}

?>
-----
<?php

use TYPO3\CMS\Backend\Controller\SimpleDataHandlerController;

final class MySimpleDataHandlerController extends SimpleDataHandlerController
{
    public function myMethod()
    {
    }
}

?>
