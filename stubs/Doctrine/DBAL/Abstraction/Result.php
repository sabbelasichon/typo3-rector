<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Abstraction;

use Doctrine\DBAL\Driver\Result as DriverResult;
use Traversable;

if (interface_exists('Doctrine\DBAL\Abstraction\Result')) {
    return;
}

interface Result extends DriverResult
{
    public function iterateNumeric(): Traversable;

    public function iterateAssociative(): Traversable;

    public function iterateColumn(): Traversable;
}
