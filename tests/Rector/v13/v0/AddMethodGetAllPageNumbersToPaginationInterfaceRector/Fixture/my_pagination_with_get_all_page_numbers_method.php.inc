<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\AddMethodGetAllPageNumbersToPaginationInterfaceRector\Fixture;

use TYPO3\CMS\Core\Pagination\PaginationInterface;

class MySpecialPaginationWithGetAllPageNumbbersMethod implements PaginationInterface
{


    public function getFirstPageNumber(): int
    {
        return 0;
    }

    public function getLastPageNumber(): int
    {
        return 1;
    }

    /**
     * @return int[]
     */
    public function getAllPageNumbers(): array
    {
    }
}

?>
