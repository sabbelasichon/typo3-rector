<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Http;

if (class_exists(RequestFactory::class)) {
    return;
}

class RequestFactory
{
    public function request(string $uri, string $method = 'GET', array $options = []): void
    {
    }
}
