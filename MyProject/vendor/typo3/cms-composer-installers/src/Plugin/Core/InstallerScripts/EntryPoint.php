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

namespace TYPO3\CMS\Composer\Plugin\Core\InstallerScripts;

use Composer\Script\Event;
use Composer\Util\Filesystem;
use TYPO3\CMS\Composer\Plugin\Config;
use TYPO3\CMS\Composer\Plugin\Core\InstallerScript;

class EntryPoint implements InstallerScript
{
    /**
     * Absolute path to entry script source
     *
     * @var string
     */
    private $source;

    /**
     * The target file relative to the web directory
     *
     * @var string
     */
    private $target;

    public function __construct(string $source, string $target)
    {
        $this->source = $source;
        $this->target = $target;
    }

    public function run(Event $event): bool
    {
        $composer = $event->getComposer();
        $filesystem = new Filesystem();
        $pluginConfig = Config::load($composer);

        $entryPointContent = file_get_contents($this->source);
        $targetFile = $pluginConfig->get('web-dir') . '/' . $this->target;
        $autoloadFile = $composer->getConfig()->get('vendor-dir') . '/autoload.php';

        $entryPointContent = preg_replace(
            '/__DIR__ . \'[^\']*\'/',
            $filesystem->findShortestPathCode($targetFile, $autoloadFile),
            $entryPointContent
        );

        $filesystem->ensureDirectoryExists(dirname($targetFile));
        file_put_contents($targetFile, $entryPointContent);

        return true;
    }
}
