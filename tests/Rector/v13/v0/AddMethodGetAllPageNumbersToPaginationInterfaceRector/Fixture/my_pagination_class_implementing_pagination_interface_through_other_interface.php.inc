<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\AddMethodGetAllPageNumbersToPaginationInterfaceRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v13\v0\AddMethodGetAllPageNumbersToPaginationInterfaceRector\Source\MyOtherPaginationInterface;
class MySpecialPaginationClassImplementingPaginationInterfaceThroughOtherInterface implements MyOtherPaginationInterface
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

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\AddMethodGetAllPageNumbersToPaginationInterfaceRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v13\v0\AddMethodGetAllPageNumbersToPaginationInterfaceRector\Source\MyOtherPaginationInterface;
class MySpecialPaginationClassImplementingPaginationInterfaceThroughOtherInterface implements MyOtherPaginationInterface
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
        return range($this->getFirstPageNumber(), $this->getLastPageNumber());
    }
}

?>
