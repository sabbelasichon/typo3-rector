<?php

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class MyViewHelperAccessingControllerContext extends AbstractViewHelper
{
    protected $controllerContext;

    public function render()
    {
        $controllerContext = $this->controllerContext;
    }
}

class AccessingControllerContext
{
    protected $controllerContext;

    public function render()
    {
        $controllerContext = $this->controllerContext;
    }
}

class CanonicalViewHelper extends AbstractTagBasedViewHelper
{
    protected $controllerContext;

    public function render()
    {
        $uriBuilder = $this->controllerContext->getUriBuilder();
    }
}

?>
-----
<?php

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class MyViewHelperAccessingControllerContext extends AbstractViewHelper
{
    public function render()
    {
        $controllerContext = $this->renderingContext->getControllerContext();
    }
}

class AccessingControllerContext
{
    protected $controllerContext;

    public function render()
    {
        $controllerContext = $this->controllerContext;
    }
}

class CanonicalViewHelper extends AbstractTagBasedViewHelper
{
    public function render()
    {
        $uriBuilder = $this->renderingContext->getControllerContext()->getUriBuilder();
    }
}

?>
