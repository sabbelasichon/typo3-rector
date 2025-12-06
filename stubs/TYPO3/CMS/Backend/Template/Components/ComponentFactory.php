<?php

namespace TYPO3\CMS\Backend\Template\Components;

use TYPO3\CMS\Backend\Template\Components\Buttons\Action\ShortcutButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\DropDownButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\FullyRenderedButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\GenericButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\InputButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\LinkButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\SplitButton;
use TYPO3\CMS\Backend\Template\Components\Menu\Menu;
use TYPO3\CMS\Backend\Template\Components\Menu\MenuItem;

if (class_exists('TYPO3\CMS\Backend\Template\Components\ComponentFactory')) {
    return;
}

class ComponentFactory
{
    public function createBackButton(string $returnUrl): LinkButton
    {
    }

    public function createCloseButton(string $closeUrl): LinkButton
    {
    }

    public function createRefreshButton(string $requestUri): LinkButton
    {
    }

    public function createSaveButton(string $formName = ''): InputButton
    {
    }

    public function createGenericButton(): GenericButton
    {
    }
    public function createInputButton(): InputButton
    {
    }
    public function createSplitButton(): SplitButton
    {
    }
    public function createDropDownButton(): DropDownButton
    {
    }
    public function createLinkButton(): LinkButton
    {
    }
    public function createFullyRenderedButton(): FullyRenderedButton
    {
    }
    public function createShortcutButton(): ShortcutButton
    {
    }
    public function createMenuItem(): MenuItem
    {
    }
    public function createMenu(): Menu
    {
    }
}
