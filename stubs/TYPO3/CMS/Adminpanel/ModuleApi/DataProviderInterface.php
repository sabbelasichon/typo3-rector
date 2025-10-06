<?php

namespace TYPO3\CMS\Adminpanel\ModuleApi;

use Psr\Http\Message\ServerRequestInterface;

if (interface_exists('TYPO3\CMS\Adminpanel\ModuleApi\DataProviderInterface')) {
    return;
}

interface DataProviderInterface
{
    public function getDataToStore(ServerRequestInterface $request): ModuleData;
}
