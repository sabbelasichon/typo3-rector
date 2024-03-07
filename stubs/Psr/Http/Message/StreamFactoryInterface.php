<?php
namespace Psr\Http\Message;

if (interface_exists('Psr\Http\Message\StreamFactoryInterface')) {
    return;
}

interface StreamFactoryInterface
{
}
