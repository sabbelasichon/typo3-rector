<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v3\SwitchBehaviorOfArrayUtilityMethodsRector\Fixture;

use TYPO3\CMS\Core\Utility\ArrayUtility;
$bar = ArrayUtility::arrayDiffAssocRecursive([], [], false);
$test = ArrayUtility::arrayDiffAssocRecursive([], []);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v3\SwitchBehaviorOfArrayUtilityMethodsRector\Fixture;

use TYPO3\CMS\Core\Utility\ArrayUtility;
$bar = ArrayUtility::arrayDiffKeyRecursive([], []);
$test = ArrayUtility::arrayDiffKeyRecursive([], []);
?>
