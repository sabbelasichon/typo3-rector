<?php

namespace TYPO3Fluid\Fluid\Core\Compiler;

if (class_exists('TYPO3Fluid\Fluid\Core\Compiler\StopCompilingException')) {
    return;
}

class StopCompilingException extends \TYPO3Fluid\Fluid\Core\Exception {}
