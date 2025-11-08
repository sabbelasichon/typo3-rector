<?php

namespace TYPO3\CMS\Backend\Template\Components;

use TYPO3\CMS\Backend\Template\Components\Buttons\Action\ShortcutButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\ButtonInterface;
use TYPO3\CMS\Backend\Template\Components\Buttons\DropDownButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\FullyRenderedButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\GenericButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\InputButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\LinkButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\SplitButton;

if (class_exists('TYPO3\CMS\Backend\Template\Components\ButtonBar')) {
    return;
}

class ButtonBar
{
    public const BUTTON_POSITION_LEFT = 'left';

    public function makeGenericButton(): GenericButton
    {
    }

    public function makeInputButton(): InputButton
    {
    }

    public function makeSplitButton(): SplitButton
    {
    }

    public function makeDropDownButton(): DropDownButton
    {
    }

    public function makeLinkButton(): LinkButton
    {
    }

    public function makeFullyRenderedButton(): FullyRenderedButton
    {
    }

    public function makeShortcutButton(): ShortcutButton
    {
    }

    public function addButton(
        ButtonInterface $button,
        string $buttonPosition = self::BUTTON_POSITION_LEFT,
        int $buttonGroup = 1
    ): ButtonBar {
    }

    public function makeButton(string $button): ButtonInterface
    {
    }
}
