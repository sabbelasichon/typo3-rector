<?php

namespace TYPO3Fluid\Fluid\View;

use TYPO3Fluid\Fluid;

if (class_exists('TYPO3Fluid\Fluid\View\Exception')) {
    return;
}

class Exception extends Fluid\Exception {}
