<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Configuration;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

if (interface_exists('TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface')) {
    return;
}

interface ConfigurationManagerInterface
{
    public function getContentObject(): ?ContentObjectRenderer;
}
