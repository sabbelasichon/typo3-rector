<?php

namespace TYPO3\CMS\Frontend\ContentObject\Exception;

use TYPO3\CMS\Frontend\ContentObject\AbstractContentObject;

if(interface_exists('TYPO3\CMS\Frontend\ContentObject\Exception\ExceptionHandlerInterface')) {
    return;
}

interface ExceptionHandlerInterface
{
    public function handle(\Exception $exception, AbstractContentObject $contentObject = null, $contentObjectConfiguration = []): void;
}
