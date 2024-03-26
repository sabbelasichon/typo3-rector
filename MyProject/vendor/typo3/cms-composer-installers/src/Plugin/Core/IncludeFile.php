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

namespace TYPO3\CMS\Composer\Plugin\Core;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Util\Filesystem;
use TYPO3\CMS\Composer\Plugin\Core\IncludeFile\TokenInterface;

class IncludeFile
{
    const INCLUDE_FILE = '/typo3/autoload-include.php';
    const INCLUDE_FILE_TEMPLATE = '/res/php/autoload-include.tmpl.php';

    /**
     * @var TokenInterface[]
     */
    private $tokens;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var IOInterface
     */
    private $io;

    /**
     * @var Composer
     */
    private $composer;

    /**
     * IncludeFile constructor.
     *
     * @param TokenInterface[] $tokens
     * @param Filesystem $filesystem
     */
    public function __construct(IOInterface $io, Composer $composer, array $tokens, Filesystem $filesystem = null)
    {
        $this->io = $io;
        $this->composer = $composer;
        $this->tokens = $tokens;
        $this->filesystem = $filesystem ?: new Filesystem();
    }

    public function register()
    {
        $this->io->writeError('<info>Register typo3/cms-composer-installer file in root package autoload definition</info>', true, IOInterface::VERBOSE);

        // Generate and write the file
        $includeFile = $this->composer->getConfig()->get('vendor-dir') . self::INCLUDE_FILE;
        file_put_contents($includeFile, $this->getIncludeFileContent(dirname($includeFile)));

        // Register the file in the root package
        $rootPackage = $this->composer->getPackage();
        $autoloadDefinition = $rootPackage->getAutoload();
        $autoloadDefinition['files'][] = $includeFile;
        $rootPackage->setAutoload($autoloadDefinition);

        // Load it to expose the paths to further plugin functionality
        require $includeFile;
    }

    /**
     * Constructs the include file content
     *
     * @param string $includeFilePath
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @return string
     */
    protected function getIncludeFileContent(string $includeFilePath)
    {
        $includeFileTemplate = $this->filesystem->normalizePath(dirname(dirname(dirname(__DIR__))) . self::INCLUDE_FILE_TEMPLATE);
        $includeFileContent = file_get_contents($includeFileTemplate);
        foreach ($this->tokens as $token) {
            $includeFileContent = self::replaceToken($token->getName(), $token->getContent($includeFilePath), $includeFileContent);
        }
        return $includeFileContent;
    }

    /**
     * Replaces a token in the subject (PHP code)
     *
     * @param string $name
     * @param string $content
     * @param string $subject
     * @return string
     */
    private static function replaceToken($name, $content, $subject)
    {
        return str_replace('\'{$' . $name . '}\'', $content, $subject);
    }
}
