<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\Fixture;

use TYPO3\CMS\Domain\Repository\PageRepository;

// This case is too complicated as we don't know the other logic
$value = PageRepository::DOKTYPE_RECYCLER;
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\Fixture;

use TYPO3\CMS\Domain\Repository\PageRepository;

// This case is too complicated as we don't know the other logic
$value = PageRepository::DOKTYPE_RECYCLER;
?>
