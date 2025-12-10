<?php

namespace TYPO3\CMS\Core\Imaging;

if (class_exists('TYPO3\CMS\Core\Imaging\IconRegistry')) {
    return;
}

class IconRegistry
{
    public function registerIcon($identifier, $iconProviderClassName, array $options = []): void
    {
    }
}
