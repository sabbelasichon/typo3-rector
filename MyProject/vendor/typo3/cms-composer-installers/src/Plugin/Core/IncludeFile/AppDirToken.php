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

namespace TYPO3\CMS\Composer\Plugin\Core\IncludeFile;

use Composer\IO\IOInterface;
use Composer\Util\Filesystem;
use TYPO3\CMS\Composer\Plugin\Config as Typo3PluginConfig;

class AppDirToken implements TokenInterface
{
    /**
     * @var string
     */
    private $name = 'app-dir';

    /**
     * @var Typo3PluginConfig
     */
    private $typo3PluginConfig;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var IOInterface
     */
    private $io;

    /**
     * BaseDirToken constructor.
     *
     * @param IOInterface $io
     * @param Typo3PluginConfig $typo3PluginConfig
     * @param Filesystem $filesystem
     */
    public function __construct(IOInterface $io, Typo3PluginConfig $typo3PluginConfig, Filesystem $filesystem = null)
    {
        $this->io = $io;
        $this->typo3PluginConfig = $typo3PluginConfig;
        $this->filesystem = $filesystem ?: new Filesystem();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $includeFilePath
     * @throws \InvalidArgumentException
     * @return string
     */
    public function getContent(string $includeFilePath)
    {
        return $this->filesystem->findShortestPathCode(
            $includeFilePath,
            $this->typo3PluginConfig->get('app-dir'),
            true
        );
    }
}
