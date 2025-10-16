<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\Fixture;

use TYPO3\CMS\Core\Domain\Repository\PageRepository;

$row = true;

if ($row === false
    || (int)$row['mode'] === PageRepository::SHORTCUT_MODE_RANDOM_SUBPAGE
    || (int)$row['mode'] === PageRepository::DOKTYPE_BE_USER_SECTION
) {
    // do something
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\Fixture;

use TYPO3\CMS\Core\Domain\Repository\PageRepository;

$row = true;

if ($row === false
    || (int)$row['mode'] === PageRepository::DOKTYPE_BE_USER_SECTION
) {
    // do something
}

?>
