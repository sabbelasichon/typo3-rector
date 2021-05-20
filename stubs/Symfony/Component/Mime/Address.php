<?php
declare(strict_types=1);

namespace Symfony\Component\Mime;

if (class_exists('Symfony\Component\Mime\Address')) {
    return;
}

class Address
{
    public function __construct(string $address, string $name = '')
    {
    }
}
