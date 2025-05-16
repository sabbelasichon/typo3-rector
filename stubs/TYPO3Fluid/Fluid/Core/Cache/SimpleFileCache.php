<?php

namespace TYPO3Fluid\Fluid\Core\Cache;

class SimpleFileCache implements FluidCacheInterface
{
    public const DIRECTORY_DEFAULT = 'cache';

    protected string $directory = self::DIRECTORY_DEFAULT;

    public function __construct(string $directory = self::DIRECTORY_DEFAULT)
    {
        $this->directory = rtrim($directory, '/') . '/';
    }
}
