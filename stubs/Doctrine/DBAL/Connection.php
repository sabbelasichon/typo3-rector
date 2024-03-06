<?php

declare(strict_types=1);

namespace Doctrine\DBAL;

if (class_exists('Doctrine\DBAL\Connection')) {
    return;
}

class Connection
{
    public function quote(string $input, int $type): void
    {}
}
