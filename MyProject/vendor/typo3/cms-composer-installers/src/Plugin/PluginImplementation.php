<?php
declare(strict_types=1);

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

namespace TYPO3\CMS\Composer\Plugin;

use Composer\Composer;
use Composer\Script\Event;
use Composer\Util\Filesystem;
use TYPO3\CMS\Composer\Plugin\Config as PluginConfig;
use TYPO3\CMS\Composer\Plugin\Core\IncludeFile;
use TYPO3\CMS\Composer\Plugin\Core\IncludeFile\AppDirToken;
use TYPO3\CMS\Composer\Plugin\Core\IncludeFile\BaseDirToken;
use TYPO3\CMS\Composer\Plugin\Core\IncludeFile\ComposerModeToken;
use TYPO3\CMS\Composer\Plugin\Core\IncludeFile\RootDirToken;
use TYPO3\CMS\Composer\Plugin\Core\IncludeFile\WebDirToken;
use TYPO3\CMS\Composer\Plugin\Core\ScriptDispatcher;

/**
 * Implementation of the Plugin to make further changes more robust on Composer updates
 */
class PluginImplementation
{
    /**
     * @var ScriptDispatcher
     */
    private $scriptDispatcher;

    /**
     * @var IncludeFile
     */
    private $includeFile;

    /**
     * @var Composer
     */
    private $composer;

    /**
     * @param Event $event
     * @param ScriptDispatcher $scriptDispatcher
     * @param IncludeFile $includeFile
     */
    public function __construct(
        Event $event,
        ScriptDispatcher $scriptDispatcher = null,
        IncludeFile $includeFile = null
    ) {
        $io = $event->getIO();
        $this->composer = $event->getComposer();
        $fileSystem = new Filesystem();
        $pluginConfig = PluginConfig::load($this->composer, $io);

        $this->scriptDispatcher = $scriptDispatcher ?: new ScriptDispatcher($event);
        $this->includeFile = $includeFile
            ?: new IncludeFile(
                $io,
                $this->composer,
                [
                    new BaseDirToken($io, $pluginConfig),
                    new AppDirToken($io, $pluginConfig),
                    new WebDirToken($io, $pluginConfig),
                    new RootDirToken($io, $pluginConfig),
                    new ComposerModeToken($io, $pluginConfig),
                ],
                $fileSystem
            );
    }

    public function preAutoloadDump()
    {
        if ($this->composer->getPackage()->getName() === 'typo3/cms') {
            // Nothing to do typo3/cms is root package
            return;
        }
        $this->includeFile->register();
    }

    public function postAutoloadDump()
    {
        $this->scriptDispatcher->executeScripts();
    }
}
