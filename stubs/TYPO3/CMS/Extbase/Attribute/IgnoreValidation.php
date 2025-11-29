<?php

namespace TYPO3\CMS\Extbase\Attribute;

if (class_exists('TYPO3\CMS\Extbase\Attribute\IgnoreValidation')) {
    return;
}

class IgnoreValidation
{
    public function __construct($argumentName = null)
    {
    }
}
