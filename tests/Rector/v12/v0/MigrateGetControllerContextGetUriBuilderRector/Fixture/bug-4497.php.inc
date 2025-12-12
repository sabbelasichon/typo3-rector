<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateGetControllerContextGetUriBuilderRector\Fixture;

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class EventListener
{
    public function __invoke()
    {
        $uri = $this->getUriBuilder()
            ->buildUriFromRoute(
                'my-route',
                [
                    'foo' => 'bar',
                    'baz' => 1,
                ]
            );
    }

    private function getUriBuilder(): UriBuilder
    {
        return GeneralUtility::makeInstance(UriBuilder::class);
    }
}
