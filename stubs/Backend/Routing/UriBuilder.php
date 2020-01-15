<?php

declare(strict_types=1);

namespace TYPO3\CMS\Backend\Routing;

final class UriBuilder
{
    private const ABSOLUTE_PATH = 'bar';

    public function buildUriFromRoute($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH): string
    {
        return 'foo';
    }
}
