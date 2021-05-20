<?php
declare(strict_types=1);


namespace TYPO3\CMS\Core\Core;

if (class_exists('TYPO3\CMS\Core\Core\ClassLoadingInformation')) {
    return;
}

class ClassLoadingInformation
{

    public static function setClassLoader($classLoader)
    {
    }

    public static function isClassLoadingInformationAvailable(): bool
    {
        return true;
    }

    public static function registerClassLoadingInformation()
    {
    }
}
