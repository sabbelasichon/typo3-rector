<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\UseStrictTypesInFluidViewHelpersRector\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class MyConditionViewHelper extends AbstractConditionViewHelper
{
    public function render()
    {
    }
    public static function verdict(array $arguments, RenderingContextInterface $renderingContext)
    {
        return true;
    }
    public function renderThenChild()
    {
    }
    public function renderElseChild()
    {
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\UseStrictTypesInFluidViewHelpersRector\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class MyConditionViewHelper extends AbstractConditionViewHelper
{
    public function render(): mixed
    {
    }
    public static function verdict(array $arguments, RenderingContextInterface $renderingContext): bool
    {
        return true;
    }
    public function renderThenChild(): mixed
    {
    }
    public function renderElseChild(): mixed
    {
    }
}
?>
