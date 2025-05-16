<?php

namespace TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression;

if (class_exists('TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\ExpressionException')) {
    return;
}

class ExpressionException extends \TYPO3Fluid\Fluid\Core\Exception {}
