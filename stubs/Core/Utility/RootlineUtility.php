<?php
declare(strict_types=1);


namespace TYPO3\CMS\Core\Utility;

if (class_exists(RootlineUtility::class)) {
    return;
}

final class RootlineUtility
{
    public function __construct($uid, $mountPointParameter = '', $context = null)
    {

    }

    public function get(): array
    {
        return [];
    }
}
