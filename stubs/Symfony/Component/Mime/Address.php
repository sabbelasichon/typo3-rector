<?php
declare(strict_types=1);

namespace Symfony\Component\Mime;

if (class_exists(Address::class)) {
    return;
}

class Address
{
    public function __construct(string $address, string $name = '')
    {
    }
}
