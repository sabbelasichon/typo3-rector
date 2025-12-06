<?php

namespace TYPO3\CMS\Backend\Template\Components\Buttons;

if (class_exists('TYPO3\CMS\Backend\Template\Components\Buttons\LinkButton')) {
    return;
}

class LinkButton extends AbstractButton
{
    public function setHref(string $href): LinkButton
    {
    }

    public function setDataAttributes(array $array): LinkButton
    {
    }

    public function setClasses(string $string): LinkButton
    {
    }
}
