<?php


namespace TYPO3\CMS\Backend\Routing;

if (class_exists(UriBuilder::class)) {
    return;
}

class UriBuilder
{
    private const ABSOLUTE_PATH = 'bar';

    public function buildUriFromRoute($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH): string
    {
        return 'foo';
    }
}
