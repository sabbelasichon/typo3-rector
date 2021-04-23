<?php
declare(strict_types=1);

namespace TYPO3\CMS\IndexedSearch\Utility;

if (class_exists(LikeWildcard::class)) {
    return;
}

class LikeWildcard
{
    public const WILDCARD_LEFT = 'foo';
    public const WILDCARD_RIGHT = 'foo';
}
