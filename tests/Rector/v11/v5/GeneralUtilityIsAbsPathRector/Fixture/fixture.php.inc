<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\GeneralUtilityIsAbsPathRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::isAbsPath('dummy');
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\GeneralUtilityIsAbsPathRector\Fixture;

use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

PathUtility::isAbsolutePath('dummy');
?>
