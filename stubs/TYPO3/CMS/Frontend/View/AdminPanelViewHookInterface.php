<?php

declare(strict_types=1);

namespace TYPO3\CMS\Frontend\View;;

if (interface_exists(AdminPanelViewHookInterface::class)) {
    return;
}

interface AdminPanelViewHookInterface
{
}
