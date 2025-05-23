<?php

namespace TYPO3\CMS\Core\Pagination;

if(interface_exists(PaginationInterface::class)) {
    return;
}

interface PaginationInterface
{
    public function getAllPageNumbers(): array;

    public function getFirstPageNumber(): int;

    public function getLastPageNumber(): int;
}
