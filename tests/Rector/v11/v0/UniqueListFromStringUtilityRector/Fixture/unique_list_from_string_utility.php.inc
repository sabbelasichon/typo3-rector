<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\UniqueListFromStringUtilityRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::uniqueList('1,2,2,3');

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\UniqueListFromStringUtilityRector\Fixture;

use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

StringUtility::uniqueList('1,2,2,3');

?>
