<?php

namespace TYPO3\CMS\Backend\Template\Components\Buttons;

use TYPO3\CMS\Backend\Template\Components\AbstractControl;
use TYPO3\CMS\Core\Imaging\Icon;

if (class_exists('TYPO3\CMS\Backend\Template\Components\Buttons\AbstractButton')) {
    return;
}

class AbstractButton extends AbstractControl implements ButtonInterface
{
    public function setIcon(?Icon $icon): AbstractButton
    {
    }
}
