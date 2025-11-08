<?php

namespace TYPO3\CMS\Backend\Template\Components\Buttons\Action;

use TYPO3\CMS\Backend\Template\Components\Buttons\ButtonInterface;

if (class_exists('TYPO3\CMS\Backend\Template\Components\Buttons\Action\ShortcutButton')) {
    return;
}

class ShortcutButton implements ButtonInterface
{
}
