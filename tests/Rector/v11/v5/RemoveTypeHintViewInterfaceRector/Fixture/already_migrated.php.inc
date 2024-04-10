<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\RemoveTypeHintViewInterfaceRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

class MyActionController extends ActionController
{
    /**
     * @param ViewInterface $view
     */
    protected function initializeView($view) {}
}
