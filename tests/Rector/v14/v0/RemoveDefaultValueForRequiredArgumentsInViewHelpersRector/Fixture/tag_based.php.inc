<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveDefaultValueForRequiredArgumentsInViewHelpersRector\Fixture;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

class MyTagBasedViewHelper extends AbstractTagBasedViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('myId', 'int', 'My id', true, 1);
        $this->registerArgument('keep', 'int', 'Keep this one', false, 2);
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveDefaultValueForRequiredArgumentsInViewHelpersRector\Fixture;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

class MyTagBasedViewHelper extends AbstractTagBasedViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('myId', 'int', 'My id', true);
        $this->registerArgument('keep', 'int', 'Keep this one', false, 2);
    }
}
?>
