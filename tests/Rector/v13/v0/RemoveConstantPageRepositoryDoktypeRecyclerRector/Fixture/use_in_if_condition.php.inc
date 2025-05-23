<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\Fixture;

use TYPO3\CMS\Domain\Repository\PageRepository;

$foo = PageRepository::DOKTYPE_DEFAULT;

// This case is too complicated
if ($foo === PageRepository::DOKTYPE_RECYCLER) {
    echo 'recycler';
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\Fixture;

use TYPO3\CMS\Domain\Repository\PageRepository;

$foo = PageRepository::DOKTYPE_DEFAULT;

// This case is too complicated
if ($foo === PageRepository::DOKTYPE_RECYCLER) {
    echo 'recycler';
}
?>
