<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveRandomSubpageOptionRector\Fixture;

use TYPO3\CMS\Core\Domain\Repository\PageRepository;

$excludeDoktypes = [
    PageRepository::SHORTCUT_MODE_RANDOM_SUBPAGE,
    PageRepository::DOKTYPE_SYSFOLDER,
    PageRepository::DOKTYPE_SPACER,
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveRandomSubpageOptionRector\Fixture;

use TYPO3\CMS\Core\Domain\Repository\PageRepository;

$excludeDoktypes = [
    PageRepository::DOKTYPE_SYSFOLDER,
    PageRepository::DOKTYPE_SPACER,
];

?>
