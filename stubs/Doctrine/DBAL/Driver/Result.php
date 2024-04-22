<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Driver;

if (interface_exists('Doctrine\DBAL\Driver\Result')) {
    return;
}

interface Result
{
    public function fetchNumeric();

    public function fetchAssociative();

    public function fetchColumn($columnIndex = 0);
    public function fetchOne($columnIndex = 0);

    public function fetchAllNumeric(): array;

    public function fetchAll(): array;

    public function fetchAllAssociative(): array;

    public function fetchFirstColumn(): array;

    public function rowCount();

    public function columnCount();

    public function free(): void;
}
