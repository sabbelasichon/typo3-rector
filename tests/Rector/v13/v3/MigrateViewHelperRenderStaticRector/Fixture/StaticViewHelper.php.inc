<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateViewHelperRenderStaticRector\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class StaticViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    public function initializeArguments(): void
    {
        $this->registerArgument('arg1', 'string', '');
        $this->registerArgument('arg2', 'string', '');
    }

    public static function renderStatic(array $myArguments, \Closure $myClosure, RenderingContextInterface $myContext): string
    {
        $var1 = $myArguments['arg1'];
        if ($myArguments['arg2']) {
            $var1 = $myArguments['arg2'];
        }
        $var1 = $var1 ?? $myClosure();
        $var2 = $myContext->getVariableProvider()->get('test');
        $var3 = array_map(fn ($item) => $myContext->getVariableProvider($item) ?? $item, $this->arguments['arg3']);
        return implode(', ', $var3);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateViewHelperRenderStaticRector\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class StaticViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('arg1', 'string', '');
        $this->registerArgument('arg2', 'string', '');
    }

    public function render(): string
    {
        $var1 = $this->arguments['arg1'];
        if ($this->arguments['arg2']) {
            $var1 = $this->arguments['arg2'];
        }
        $var1 = $var1 ?? $this->renderChildren();
        $var2 = $this->renderingContext->getVariableProvider()->get('test');
        $var3 = array_map(fn ($item) => $this->renderingContext->getVariableProvider($item) ?? $item, $this->arguments['arg3']);
        return implode(', ', $var3);
    }
}

?>
