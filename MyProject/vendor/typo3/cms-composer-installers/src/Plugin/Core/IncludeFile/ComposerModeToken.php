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
use TYPO3\CMS\Composer\Plugin\Config as PluginConfig;

class ComposerModeToken implements TokenInterface
{
    /**
     * @var string
     */
    private $name = 'composer-mode';

    /**
     * @var IOInterface
     */
    private $io;

    /**
     * @var PluginConfig
     */
    private $pluginConfig;

    /**
     * WebDirToken constructor.
     *
     * @param IOInterface $io
     * @param PluginConfig $pluginConfig
     */
    public function __construct(IOInterface $io, PluginConfig $pluginConfig)
    {
        $this->io = $io;
        $this->pluginConfig = $pluginConfig;
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
        if (!$this->pluginConfig->get('composer-mode')) {
            return 'TYPO3 is installed via composer, but for development reasons the additional class loader is activated. Handle with care!';
        }

        $this->io->writeError('<info>Inserting TYPO3_COMPOSER_MODE constant</info>', true, IOInterface::VERBOSE);

        return <<<COMPOSER_MODE
TYPO3 is installed via composer. Flag this with a constant.
if (!defined('TYPO3_COMPOSER_MODE')) {
    define('TYPO3_COMPOSER_MODE', TRUE);
}
COMPOSER_MODE;
    }
}
