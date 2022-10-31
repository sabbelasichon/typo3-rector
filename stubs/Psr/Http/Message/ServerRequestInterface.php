<?php
namespace Psr\Http\Message;

if (interface_exists('Psr\Http\Message\ServerRequestInterface')) {
    return;
}

interface ServerRequestInterface
{

    /**
     * @return mixed
     */
    public function getAttribute($name, $default);
}
