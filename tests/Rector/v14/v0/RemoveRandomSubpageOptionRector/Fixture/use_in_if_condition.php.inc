<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\Fixture;

use TYPO3\CMS\Core\Domain\Repository\PageRepository;

$foo = PageRepository::DOKTYPE_DEFAULT;

// This case is too complicated
if ($foo === PageRepository::SHORTCUT_MODE_RANDOM_SUBPAGE) {
    echo 'shortcut';
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\Fixture;

use TYPO3\CMS\Core\Domain\Repository\PageRepository;

$foo = PageRepository::DOKTYPE_DEFAULT;

// This case is too complicated
if ($foo === PageRepository::SHORTCUT_MODE_RANDOM_SUBPAGE) {
    echo 'shortcut';
}
?>
