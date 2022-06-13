<?php

declare(strict_types=1);

namespace Doctrine\DBAL;

if (class_exists('Doctrine\DBAL\Result')) {
    return;
}

class Result
{
    public function fetchAllAssociative(): array
    {
        return [];
    }
}
