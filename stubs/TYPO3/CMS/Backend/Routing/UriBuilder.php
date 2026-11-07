<?php

namespace TYPO3\CMS\Backend\Routing;

if (class_exists('TYPO3\CMS\Backend\Routing\UriBuilder')) {
    return;
}

class UriBuilder
{
    const ABSOLUTE_PATH = 'bar';

    /**
     * @return \Psr\Http\Message\UriInterface
     */
    public function buildUriFromRoute($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        return 'foo';
    }
}
