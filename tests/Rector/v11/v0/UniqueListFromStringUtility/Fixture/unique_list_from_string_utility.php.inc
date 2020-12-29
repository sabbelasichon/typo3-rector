<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\UniqueListFromStringUtility\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::uniqueList('1,2,2,3');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\UniqueListFromStringUtility\Fixture;

use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

StringUtility::uniqueList('1,2,2,3');

?>
