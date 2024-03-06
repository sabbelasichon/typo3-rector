<?php

declare(strict_types=1);

namespace Rector\v13\v0\AddMethodGetAllPageNumbersToPaginationInterfaceRector\Source;

use TYPO3\CMS\Core\Pagination\PaginationInterface;

abstract class MySpecialAbstractPagination implements PaginationInterface
{
    public function getFirstPageNumber(): int
    {
        return 0;
    }

    public function getLastPageNumber(): int
    {
        return 1;
    }
}
