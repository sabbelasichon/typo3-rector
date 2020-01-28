<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Core;

if (class_exists(Environment::class)) {
    return;
}

final class Environment
{
    public static function isCli(): bool
    {
    }

    public static function getProjectPath(): string
    {
    }
}
