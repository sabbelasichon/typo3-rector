<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Http;

if (class_exists('TYPO3\CMS\Core\Http\Uri')) {
    return;
}

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{

    public function __construct($uri = '')
    {
    }

    public function __toString()
    {
        return '';
    }
}