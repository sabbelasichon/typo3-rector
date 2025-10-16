<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveRandomSubpageOptionRector\Fixture;

use TYPO3\CMS\Core\Domain\Repository\PageRepository;

$pageRepository = new PageRepository();
$page = $pageRepository->resolveShortcutPage([], false, true);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveRandomSubpageOptionRector\Fixture;

use TYPO3\CMS\Core\Domain\Repository\PageRepository;

$pageRepository = new PageRepository();
$page = $pageRepository->resolveShortcutPage([], true);

?>
