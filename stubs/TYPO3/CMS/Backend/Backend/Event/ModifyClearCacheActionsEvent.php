<?php

namespace TYPO3\CMS\Backend\Backend\Event;

if (class_exists('TYPO3\CMS\Backend\Backend\Event\ModifyClearCacheActionsEvent')) {
    return;
}

final class ModifyClearCacheActionsEvent
{
    public function addCacheAction(array $cacheAction): void
    {
    }

    public function setCacheActions(array $cacheActions): void
    {
    }

    public function getCacheActions(): array
    {
        return [];
    }
}
