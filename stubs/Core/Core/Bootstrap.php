<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Core;

if (class_exists(Bootstrap::class)) {
    return;
}

final class Bootstrap
{
    public static function usesComposerClassLoading(): void
    {
    }
}
