<?php

/*
 * This file is part of the TYPO3 project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Composer\Installer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use TYPO3\CMS\Composer\Plugin\PluginImplementation;

/**
 * The plugin that registers some event handlers
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * @var PluginImplementation
     */
    private $pluginImplementation;

    /**
     * @var array
     */
    private $handledEvents = [];

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ScriptEvents::PRE_AUTOLOAD_DUMP => ['listen'],
            ScriptEvents::POST_AUTOLOAD_DUMP => ['listen'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $composer->getEventDispatcher()->addSubscriber($this);
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
        // Nothing to do
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        // Nothing to do
    }

    /**
     * Listens to Composer events.
     *
     * This method is very minimalist on purpose. We want to load the actual
     * implementation only after updating the Composer packages so that we get
     * the updated version (if available).
     *
     * @param Event $event The Composer event.
     */
    public function listen(Event $event)
    {
        if (!empty($this->handledEvents[$event->getName()])) {
            return;
        }
        $this->handledEvents[$event->getName()] = true;
        // Plugin has been uninstalled
        if (!file_exists(__FILE__) || !file_exists(dirname(__DIR__) . '/Plugin/PluginImplementation.php')) {
            return;
        }

        // Load the implementation only after updating Composer so that we get
        // the new version of the plugin when a new one was installed
        if (null === $this->pluginImplementation) {
            $this->pluginImplementation = new PluginImplementation($event);
        }

        switch ($event->getName()) {
            case ScriptEvents::PRE_AUTOLOAD_DUMP:
                $this->pluginImplementation->preAutoloadDump();
                break;
            case ScriptEvents::POST_AUTOLOAD_DUMP:
                $this->pluginImplementation->postAutoloadDump();
                break;
        }
    }
}
