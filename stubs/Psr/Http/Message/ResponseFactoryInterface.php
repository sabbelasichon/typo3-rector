<?php
namespace Psr\Http\Message;

if (interface_exists('Psr\Http\Message\ResponseFactoryInterface')) {
    return;
}

interface ResponseFactoryInterface
{
}
