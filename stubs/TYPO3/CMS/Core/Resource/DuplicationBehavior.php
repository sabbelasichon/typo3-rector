<?php

namespace TYPO3\CMS\Core\Resource;

use TYPO3\CMS\Core\Type\Enumeration;

if (class_exists('TYPO3\CMS\Core\Resource\DuplicationBehavior')) {
    return;
}

final class DuplicationBehavior extends Enumeration
{
    public const __default = self::CANCEL;

    public const RENAME = 'rename';

    public const REPLACE = 'replace';

    public const CANCEL = 'cancel';
}
