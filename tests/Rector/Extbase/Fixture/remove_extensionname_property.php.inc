<?php

use TYPO3\CMS\Extbase\Mvc\Controller\AbstractController;

final class RemoveExtensionNameProperty extends AbstractController
{
    public function myMethod()
    {
        if($this->extensionName === 'whatever') {

        }

        $extensionName = $this->extensionName;
    }
}

?>
-----
<?php

use TYPO3\CMS\Extbase\Mvc\Controller\AbstractController;

final class RemoveExtensionNameProperty extends AbstractController
{
    public function myMethod()
    {
        if($this->request->getControllerExtensionName() === 'whatever') {

        }

        $extensionName = $this->request->getControllerExtensionName();
    }
}

?>
