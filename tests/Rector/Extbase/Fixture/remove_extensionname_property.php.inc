<?php

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

final class RemoveExtensionNameProperty extends ActionController
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

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

final class RemoveExtensionNameProperty extends ActionController
{
    public function myMethod()
    {
        if($this->request->getControllerExtensionName() === 'whatever') {

        }

        $extensionName = $this->request->getControllerExtensionName();
    }
}

?>
