<?php

use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class MyViewHelperAccessingControllerContext extends AbstractViewHelper
{
    protected $controllerContext;

    public function render()
    {
        $controllerContext = $this->controllerContext;
    }

    public function setRenderingContext(RenderingContextInterface $renderingContext)
    {
        if ($renderingContext instanceof RenderingContext) {
            $this->controllerContext = $renderingContext->getControllerContext();
        }
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

use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class MyViewHelperAccessingControllerContext extends AbstractViewHelper
{
    protected $controllerContext;

    public function render()
    {
        $controllerContext = $this->renderingContext->getControllerContext();
    }

    public function setRenderingContext(RenderingContextInterface $renderingContext)
    {
        if ($renderingContext instanceof RenderingContext) {
            $this->controllerContext = $renderingContext->getControllerContext();
        }
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
        $uriBuilder = $this->renderingContext->getControllerContext()->getUriBuilder();
    }
}

?>
