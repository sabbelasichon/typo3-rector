<?php
declare(strict_types=1);


namespace Psr\Http\Message;

if(interface_exists(ServerRequestInterface::class)) {
    return;
}

interface ServerRequestInterface
{

}
