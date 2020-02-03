<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Utility;

if (class_exists(GeneralUtility::class)) {
    return;
}

class GeneralUtility
{
    public static function getApplicationContext(): void
    {
    }

    /**
     * @param string $class
     *
     * @return object
     */
    public static function makeInstance(string $class)
    {
        return new $class();
    }

    public static function getIndpEnv(string $var): string
    {
        return 'foo';
    }

    public static function mkdir_deep(string $folder)
    {
        return 'foo';
    }
}
