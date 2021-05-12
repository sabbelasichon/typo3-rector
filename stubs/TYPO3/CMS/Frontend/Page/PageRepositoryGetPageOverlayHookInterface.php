<?php

declare(strict_types=1);

namespace TYPO3\CMS\Frontend\Page;;

if (interface_exists(PageRepositoryGetPageOverlayHookInterface::class)) {
    return;
}

interface PageRepositoryGetPageOverlayHookInterface
{
}
