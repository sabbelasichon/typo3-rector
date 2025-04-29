<?php

namespace TYPO3\CMS\Fluid\View;

use TYPO3Fluid\Fluid\View\AbstractTemplateView;

if (class_exists('TYPO3\CMS\Fluid\View\StandaloneView')) {
    return;
}

class StandaloneView extends AbstractTemplateView
{
}
