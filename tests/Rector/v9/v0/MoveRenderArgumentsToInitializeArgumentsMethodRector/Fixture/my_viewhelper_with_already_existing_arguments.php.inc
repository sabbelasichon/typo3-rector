<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Fluid\ViewHelpers\Fixture;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class SlimTitleViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('title', 'string', '', false);
        $this->registerArgument('splitByFirstSpace', 'string', '', false);
    }

    public function render($title = null, $splitByFirstSpace = false)
    {

    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Fluid\ViewHelpers\Fixture;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class SlimTitleViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('title', 'string', '', false);
        $this->registerArgument('splitByFirstSpace', 'string', '', false);
    }

    public function render()
    {
        $title = $this->arguments['title'];
        $splitByFirstSpace = $this->arguments['splitByFirstSpace'];
    }
}

?>
