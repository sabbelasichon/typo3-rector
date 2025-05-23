<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\Fixture;

use TYPO3\CMS\Domain\Repository\PageRepository;

$excludeDoktypes = [
    PageRepository::DOKTYPE_RECYCLER,
    PageRepository::DOKTYPE_SYSFOLDER,
    PageRepository::DOKTYPE_SPACER,
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\Fixture;

use TYPO3\CMS\Domain\Repository\PageRepository;

$excludeDoktypes = [
    PageRepository::DOKTYPE_SYSFOLDER,
    PageRepository::DOKTYPE_SPACER,
];

?>
