<?php

namespace TYPO3\CMS\Core\Http;

use Psr\Http\Message\ResponseInterface;

if (class_exists('TYPO3\CMS\Core\Http\Response')) {
    return;
}

class Response implements ResponseInterface
{

    public function withStatus($code, $reasonPhrase = '')
    {
        return new self();
    }
}
