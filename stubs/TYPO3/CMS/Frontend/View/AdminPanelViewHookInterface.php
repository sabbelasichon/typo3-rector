<?php

namespace TYPO3\CMS\Frontend\View;;

if (interface_exists(AdminPanelViewHookInterface::class)) {
    return;
}

interface AdminPanelViewHookInterface
{
}
