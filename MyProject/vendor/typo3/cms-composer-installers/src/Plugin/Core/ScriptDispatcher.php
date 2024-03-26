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

namespace TYPO3\CMS\Composer\Plugin\Core;

use Composer\Autoload\ClassLoader;
use Composer\Script\Event;

class ScriptDispatcher
{
    /**
     * @var Event
     */
    private $event;

    /**
     * @var ClassLoader
     */
    private $loader;

    /**
     * Array of callables that are executed after autoload dump
     *
     * @var InstallerScript[][]
     */
    private $installerScripts = [];

    /**
     * ScriptDispatcher constructor.
     *
     * @param Event $event
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @param InstallerScript $script The callable that will be executed
     * @param int $priority Higher priority results in earlier execution
     */
    public function addInstallerScript(InstallerScript $script, $priority = 50)
    {
        $this->installerScripts[$priority][] = $script;
    }

    public function executeScripts()
    {
        $io = $this->event->getIO();
        $this->registerLoader();

        ksort($this->installerScripts, SORT_NUMERIC);
        $io->writeError('<info>Executing TYPO3 installer scripts</info>', true, $io::VERBOSE);
        try {
            foreach (array_reverse($this->installerScripts) as $scripts) {
                /** @var InstallerScript $script */
                foreach ($scripts as $script) {
                    $io->writeError(sprintf('<info>Executing "%s": </info>', get_class($script)), true, $io::DEBUG);
                    if (!$script->run($this->event)) {
                        $io->writeError(sprintf('<error>Executing "%s" failed.</error>', get_class($script)), true);
                    }
                }
            }
        } catch (StopInstallerScriptExecution $e) {
            // Just skip further script execution
        } finally {
            $this->unRegisterLoader();
        }
    }

    private function registerLoader()
    {
        $composer = $this->event->getComposer();
        $package = $composer->getPackage();
        $generator = $composer->getAutoloadGenerator();
        $packages = $composer->getRepositoryManager()->getLocalRepository()->getCanonicalPackages();
        $packageMap = $generator->buildPackageMap($composer->getInstallationManager(), $package, $packages);
        $map = $generator->parseAutoloads($packageMap, $package);
        $this->loader = $generator->createLoader($map);
        $this->loader->register();
        if (!empty($map['psr-4']) && is_array($map['psr-4'])) {
            $this->registerInstallerScripts(array_keys($map['psr-4']));
        }
    }

    private function registerInstallerScripts(array $psr4Namespaces)
    {
        foreach ($psr4Namespaces as $psr4Namespace) {
            /** @var InstallerScriptsRegistration $scriptsRegistrationCandidate */
            $scriptsRegistrationCandidate = $psr4Namespace . 'Composer\\InstallerScripts';
            if (
                class_exists($scriptsRegistrationCandidate)
                && in_array(InstallerScriptsRegistration::class, class_implements($scriptsRegistrationCandidate), true)
            ) {
                $scriptsRegistrationCandidate::register($this->event, $this);
            }
        }
    }

    private function unRegisterLoader()
    {
        $this->loader->unregister();
    }
}
