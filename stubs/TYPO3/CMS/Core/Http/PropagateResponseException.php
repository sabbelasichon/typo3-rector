<?php

namespace TYPO3\CMS\Core\Http;

use Psr\Http\Message\ResponseInterface;

if (class_exists('TYPO3\CMS\Core\Http\PropagateResponseException')) {
    return;
}

final class PropagateResponseException extends ImmediateResponseException
{
    public function __construct(ResponseInterface $response, int $code = 0)
    {
        parent::__construct();
    }
}
