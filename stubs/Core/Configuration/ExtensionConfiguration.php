<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Configuration;


if (class_exists(ExtensionConfiguration::class)) {
    return;
}

final class ExtensionConfiguration
{
    /**
     * @return array
     */
    public function get(string $extension, string $path = '')
    {
        return [];
    }

    public function set(string $extension, string $path = '', $value = null): void
    {
    }

}
