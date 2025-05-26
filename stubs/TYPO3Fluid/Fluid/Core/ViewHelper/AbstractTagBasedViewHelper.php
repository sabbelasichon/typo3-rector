<?php

namespace TYPO3Fluid\Fluid\Core\ViewHelper;

use TYPO3\CMS\Fluid\Core\ViewHelper\TagBuilder;

if (class_exists('TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper')) {
    return;
}

abstract class AbstractTagBasedViewHelper extends AbstractViewHelper
{
    /**
     * @var TagBuilder
     */
    protected $tag;

    /**
     * @var array
     */
    protected $additionalArguments = [];

    protected function registerTagAttribute($name, $type, $description, $required = false, $defaultValue = null)
    {
    }

    protected function registerUniversalTagAttributes()
    {
    }
}
