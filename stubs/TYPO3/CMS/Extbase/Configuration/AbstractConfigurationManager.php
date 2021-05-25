<?php

namespace TYPO3\CMS\Extbase\Configuration;

if (class_exists('TYPO3\CMS\Extbase\Configuration\AbstractConfigurationManager')) {
    return;
}

abstract class AbstractConfigurationManager
{
    /**
     * @param string $extensionName
     * @param string $pluginName
     */
    abstract protected function getSwitchableControllerActions($extensionName, $pluginName);
}
