<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\MigrateExtbaseViewInterfaceRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyActionController extends ActionController
{
    protected function initializeView(\TYPO3Fluid\Fluid\View\AbstractView $view) {}
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\MigrateExtbaseViewInterfaceRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyActionController extends ActionController
{
    /**
     * @param \TYPO3Fluid\Fluid\View\ViewInterface $view
     */
    protected function initializeView($view) {}
}
?>
