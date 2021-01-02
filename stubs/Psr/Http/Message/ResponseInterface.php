<?php
declare(strict_types=1);

namespace Psr\Http\Message;

if(interface_exists(ResponseInterface::class)) {
    return;
}

interface ResponseInterface
{
    public function withStatus(string $code, string $reasonPhrase = ''): ResponseInterface;
}
