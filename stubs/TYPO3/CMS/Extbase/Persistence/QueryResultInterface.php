<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Persistence;

if (class_exists(QueryResultInterface::class)) {
    return;
}

interface QueryResultInterface
{
}
