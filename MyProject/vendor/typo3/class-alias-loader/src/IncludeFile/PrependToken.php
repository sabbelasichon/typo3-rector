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

use Composer\Config;
use Composer\IO\IOInterface;

class PrependToken implements TokenInterface
{
    /**
     * @var string
     */
    private $name = 'prepend';

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
        return $this->config->get('prepend-autoloader') === false ? 'false' : 'true';
    }
}
