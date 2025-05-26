<?php

namespace TYPO3\CMS\Fluid\Core\ViewHelper;

if (class_exists('TYPO3\CMS\Fluid\Core\ViewHelper\TagBuilder')) {
    return;
}

class TagBuilder
{
    public function addAttribute($attributeName, $attributeValue, $escapeSpecialCharacters = true)
    {
    }

    /**
     * @return string
     */
    public function render()
    {
        return '';
    }
}
