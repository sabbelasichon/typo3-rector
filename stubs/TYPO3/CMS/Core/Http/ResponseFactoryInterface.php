<?php
namespace TYPO3\CMS\Core\Http;

use Psr\Http\Message\ResponseInterface;

if(class_exists('TYPO3\CMS\Core\Http\ResponseFactoryInterface')) {
    return;
}

interface ResponseFactoryInterface
{
    public function createHtmlResponse(string $html): ResponseInterface;

    public function createJsonResponse(string $json): ResponseInterface;

    public function createResponse(int $statusCode): ResponseInterface;
}
