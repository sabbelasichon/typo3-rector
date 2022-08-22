<?php

namespace TYPO3\CMS\Core\Imaging;

if (class_exists('TYPO3\CMS\Core\Imaging\Icon')) {
    return;
}

class Icon
{
    const SIZE_SMALL = 'small';
    const SIZE_DEFAULT = 'default';
    const SIZE_LARGE = 'large';
    const SIZE_OVERLAY = 'overlay';
    const SIZE_MEDIUM = 'default';
}
