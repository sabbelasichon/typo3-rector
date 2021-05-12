<?php


namespace TYPO3\CMS\Frontend\Page;;

if (interface_exists(PageRepositoryInitHookInterface::class)) {
    return;
}

interface PageRepositoryInitHookInterface
{
}
