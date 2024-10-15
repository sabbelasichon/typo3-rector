<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\RenameClassMapAliasRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

$arrayBrowser = GeneralUtility::makeInstance(\TYPO3\CMS\Lowlevel\Utility\ArrayBrowser::class);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\RenameClassMapAliasRector\Fixture;

use TYPO3\CMS\Backend\View\ArrayBrowser;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$arrayBrowser = GeneralUtility::makeInstance(ArrayBrowser::class);

?>
