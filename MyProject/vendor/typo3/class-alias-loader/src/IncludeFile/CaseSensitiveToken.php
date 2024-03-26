<?php
namespace TYPO3\ClassAliasLoader\IncludeFile;

/*
 * This file is part of the class alias loader package.
 *
 * (c) Helmut Hummel <info@helhum.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Composer\IO\IOInterface;
use TYPO3\ClassAliasLoader\Config;

/**
 * @deprecated
 */
class CaseSensitiveToken implements TokenInterface
{
    /**
     * @var string
     */
    private $name = 'sensitive-loading';

    /**
     * @var IOInterface
     */
    private $io;

    /**
     * @var Config
     */
    private $config;

    /**
     * BaseDirToken constructor.
     *
     * @param IOInterface $io
     * @param Config $config
     */
    public function __construct(IOInterface $io, Config $config)
    {
        $this->io = $io;
        $this->config = $config;
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
    public function getContent($includeFilePath)
    {
        return $this->config->get('autoload-case-sensitivity') ? 'true' : 'false';
    }
}
