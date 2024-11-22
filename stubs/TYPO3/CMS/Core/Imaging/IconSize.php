<?php

namespace TYPO3\CMS\Core\Imaging;

if (class_exists('TYPO3\CMS\Core\Imaging\IconSize')) {
    return;
}

class IconSize
{
    const DEFAULT = 'default';
    const SMALL = 'small';
    const MEDIUM = 'medium';
    const LARGE = 'large';
    const MEGA = 'mega';
    /**
     * @internal
     */
    const OVERLAY = 'overlay';
}
