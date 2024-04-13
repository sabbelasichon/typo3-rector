<?php

declare(strict_types=1);

namespace Doctrine\DBAL;

use Traversable;

if (interface_exists('Doctrine\DBAL\Result')) {
    return;
}

interface Result extends Abstraction\Result
{
    public function fetchAllKeyValue(): array;

    public function fetchAllAssociativeIndexed(): array;

    public function iterateKeyValue(): Traversable;

    public function iterateAssociativeIndexed(): Traversable;
}
