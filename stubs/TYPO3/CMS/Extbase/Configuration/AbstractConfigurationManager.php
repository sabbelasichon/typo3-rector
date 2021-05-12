<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Configuration;

if (class_exists(AbstractConfigurationManager::class)) {
    return;
}

abstract class AbstractConfigurationManager
{
    abstract protected function getSwitchableControllerActions(string $extensionName, string $pluginName);
}
