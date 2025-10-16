<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\Fixture;

use TYPO3\CMS\Core\Domain\Repository\PageRepository;

// This case is too complicated as we don't know the other logic
$value = PageRepository::SHORTCUT_MODE_RANDOM_SUBPAGE;
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\Fixture;

use TYPO3\CMS\Core\Domain\Repository\PageRepository;

// This case is too complicated as we don't know the other logic
$value = PageRepository::SHORTCUT_MODE_RANDOM_SUBPAGE;
?>
