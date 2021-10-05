<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v3\SubstituteMethodRmFromListOfGeneralUtilityRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

$element = '1';
$list = '1,2,3';

$newList = GeneralUtility::rmFromList($element, $list);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v3\SubstituteMethodRmFromListOfGeneralUtilityRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

$element = '1';
$list = '1,2,3';

$newList = implode(',', array_filter(explode(',', $list), function ($item) use ($element) {
    return $element == $item;
}));

?>
