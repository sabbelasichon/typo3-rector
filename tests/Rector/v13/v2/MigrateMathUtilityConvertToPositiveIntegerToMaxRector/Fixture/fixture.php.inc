<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v2\MigrateMathUtilityConvertToPositiveIntegerToMaxRector\Fixture;

use TYPO3\CMS\Core\Utility\MathUtility;

$pageId = 4;
$var = MathUtility::convertToPositiveInteger($pageId);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v2\MigrateMathUtilityConvertToPositiveIntegerToMaxRector\Fixture;

use TYPO3\CMS\Core\Utility\MathUtility;

$pageId = 4;
$var = max(0, $pageId);
?>
