<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\UseStrictTypesInFluidViewHelpersRector\Fixture;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

class MyTagBasedViewHelper extends AbstractTagBasedViewHelper
{
    public function render()
    {
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\UseStrictTypesInFluidViewHelpersRector\Fixture;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

class MyTagBasedViewHelper extends AbstractTagBasedViewHelper
{
    public function render(): string
    {
    }
}
?>
