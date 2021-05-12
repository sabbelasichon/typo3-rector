<?php

declare(strict_types=1);

namespace TYPO3\CMS\Frontend\Page;;

if (interface_exists(PageRepositoryInitHookInterface::class)) {
    return;
}

interface PageRepositoryInitHookInterface
{
}
