<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\MigrateExtbaseViewInterfaceRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Fluid\View\StandaloneView;

class MyActionController extends ActionController
{
    /**
     * @param StandaloneView $view
     */
    protected function initializeView($view) {}
}
