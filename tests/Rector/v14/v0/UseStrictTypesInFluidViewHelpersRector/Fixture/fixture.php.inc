<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\UseStrictTypesInFluidViewHelpersRector\Fixture;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class MyViewHelper extends AbstractViewHelper
{
    public function initialize()
    {
    }

    public function initializeArguments()
    {
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\UseStrictTypesInFluidViewHelpersRector\Fixture;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class MyViewHelper extends AbstractViewHelper
{
    public function initialize(): void
    {
    }

    public function initializeArguments(): void
    {
    }
}
?>
