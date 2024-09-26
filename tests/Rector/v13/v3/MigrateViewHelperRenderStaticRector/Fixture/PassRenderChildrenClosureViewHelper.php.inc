<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateViewHelperRenderStaticRector\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class PassRenderChildrenClosureViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext): string
    {
        return self::doSomething($renderChildrenClosure);
    }

    private static function doSomething(\Closure $closure): string
    {
        return $closure();
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateViewHelperRenderStaticRector\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class PassRenderChildrenClosureViewHelper extends AbstractViewHelper
{
    public function render(): string
    {
        return self::doSomething($this->renderChildren(...));
    }

    private static function doSomething(\Closure $closure): string
    {
        return $closure();
    }
}

?>
