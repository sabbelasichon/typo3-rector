<?php

namespace TYPO3\CMS\Extbase\Configuration;

if (class_exists('TYPO3\CMS\Extbase\Configuration\AbstractConfigurationManager')) {
    return;
}

abstract class AbstractConfigurationManager
{
    abstract protected function getSwitchableControllerActions(string $extensionName, string $pluginName);
}
