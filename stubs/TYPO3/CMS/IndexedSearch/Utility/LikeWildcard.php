<?php
declare(strict_types=1);

namespace TYPO3\CMS\IndexedSearch\Utility;

if (class_exists('TYPO3\CMS\IndexedSearch\Utility\LikeWildcard')) {
    return;
}

class LikeWildcard
{
    public const WILDCARD_LEFT = 'foo';
    public const WILDCARD_RIGHT = 'foo';
}
