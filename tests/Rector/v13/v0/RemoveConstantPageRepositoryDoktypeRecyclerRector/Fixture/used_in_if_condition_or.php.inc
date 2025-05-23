<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\Fixture;

use TYPO3\CMS\Domain\Repository\PageRepository;

$row = true;

if ($row === false
    || (int)$row['doktype'] === PageRepository::DOKTYPE_RECYCLER
    || (int)$row['doktype'] === PageRepository::DOKTYPE_BE_USER_SECTION
) {
    // do something
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\Fixture;

use TYPO3\CMS\Domain\Repository\PageRepository;

$row = true;

if ($row === false
    || (int)$row['doktype'] === PageRepository::DOKTYPE_BE_USER_SECTION
) {
    // do something
}

?>
