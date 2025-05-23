<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\Fixture;

use TYPO3\CMS\Domain\Repository\PageRepository;

$row = true;

if (
    $row === true &&
    (int)$row['doktype'] === PageRepository::DOKTYPE_RECYCLER &&
    $row['isVisible']
) {
    // do something
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\Fixture;

use TYPO3\CMS\Domain\Repository\PageRepository;

$row = true;

if (
    $row === true &&
    $row['isVisible']
) {
    // do something
}
?>
