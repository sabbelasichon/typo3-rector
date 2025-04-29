<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\MigrateExtbaseViewInterfaceRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3Fluid\Fluid\View\ViewInterface;

class MyActionController extends ActionController
{
    /**
     * @param ViewInterface $view
     */
    protected function initializeView($view) {}
}
