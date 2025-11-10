<?php

namespace TYPO3\CMS\Core\SystemResource\Publishing;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use TYPO3\CMS\Core\SystemResource\Type\PublicResourceInterface;

if (interface_exists('TYPO3\CMS\Core\SystemResource\Publishing\SystemResourcePublisherInterface')) {
    return;
}

interface SystemResourcePublisherInterface
{
    public function generateUri(
        PublicResourceInterface $publicResource,
        ?ServerRequestInterface $request,
        ?UriGenerationOptions $options = null
    ): UriInterface;
}
