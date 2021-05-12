<?php


namespace TYPO3\CMS\Frontend\Page;;

if (interface_exists(PageRepositoryGetPageHookInterface::class)) {
    return;
}

interface PageRepositoryGetPageHookInterface
{
}
