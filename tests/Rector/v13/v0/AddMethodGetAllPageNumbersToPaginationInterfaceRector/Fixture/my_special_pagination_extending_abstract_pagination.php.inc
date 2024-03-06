<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\AddMethodGetAllPageNumbersToPaginationInterfaceRector\Fixture;


use Rector\v13\v0\AddMethodGetAllPageNumbersToPaginationInterfaceRector\Source\MySpecialAbstractPagination;
use TYPO3\CMS\Core\Pagination\PaginationInterface;

class MySpecialPaginationExtendingAbstractPagination extends MySpecialAbstractPagination
{
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\AddMethodGetAllPageNumbersToPaginationInterfaceRector\Fixture;

use Rector\v13\v0\AddMethodGetAllPageNumbersToPaginationInterfaceRector\Source\MySpecialAbstractPagination;
use TYPO3\CMS\Core\Pagination\PaginationInterface;

class MySpecialPaginationExtendingAbstractPagination extends MySpecialAbstractPagination
{
    /**
     * @return int[]
     */
    public function getAllPageNumbers(): array
    {
        return range($this->getFirstPageNumber(), $this->getLastPageNumber());
    }
}

?>
