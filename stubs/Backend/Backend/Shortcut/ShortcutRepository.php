<?php
declare(strict_types=1);

namespace TYPO3\CMS\Backend\Backend\Shortcut;

if (class_exists(ShortcutRepository::class)) {
    return;
}

final class ShortcutRepository
{
    public function shortcutExists(string $url): bool
    {
        return true;
    }
}
