<?php

namespace TYPO3\CMS\Backend\Template\Components;

use TYPO3\CMS\Backend\Template\Components\Menu\Menu;

if (class_exists('TYPO3\CMS\Backend\Template\Components\MenuRegistry')) {
    return;
}

class MenuRegistry
{
    public function makeMenu(): Menu
    {
    }
}
