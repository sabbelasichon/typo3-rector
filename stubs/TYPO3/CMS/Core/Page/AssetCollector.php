<?php

namespace TYPO3\CMS\Core\Page;

if (class_exists('TYPO3\CMS\Core\Page\AssetCollector')) {
    return;
}

class AssetCollector
{
    public function addJavaScript(string $identifier, string $source, array $attributes = [], array $options = []): self
    {
    }

    public function addStyleSheet(string $identifier, string $source, array $attributes = [], array $options = []): self
    {
    }
}
