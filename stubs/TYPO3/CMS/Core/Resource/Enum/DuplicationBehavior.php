<?php

namespace TYPO3\CMS\Core\Resource\Enum;

if (enum_exists('TYPO3\CMS\Core\Resource\Enum\DuplicationBehavior')) {
    return;
}

enum DuplicationBehavior: string
{
    case RENAME = 'rename';

    case REPLACE = 'replace';

    case CANCEL = 'cancel';
}
