<?php

namespace TYPO3Fluid\Fluid\Core\ViewHelper;

use TYPO3Fluid\Fluid\View\ViewInterface;

if (class_exists('TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperVariableContainer')) {
    return;
}

class ViewHelperVariableContainer
{
    public function setView(ViewInterface $view): void
    {
    }

    public function getView(): ?ViewInterface
    {
        return null;
    }
}
