<?php

namespace TYPO3\CMS\Backend\Template\Components\Menu;

if (class_exists('TYPO3\CMS\Backend\Template\Components\Menu\Menu')) {
    return;
}

class Menu
{
    public function addMenuItem(MenuItem $menuItem): Menu
    {
    }

    public function makeMenuItem(): MenuItem
    {
    }
}
