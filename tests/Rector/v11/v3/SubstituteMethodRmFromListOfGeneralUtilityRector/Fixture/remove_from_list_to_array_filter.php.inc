<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v3\SubstituteMethodRmFromListOfGeneralUtilityRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

$element = '1';
$list = '1,2,3';

$newList = GeneralUtility::rmFromList($element, $list);

$newElementVarName = '1';
$newListVarName = '1,2,3';

$newListTwo = GeneralUtility::rmFromList($newElementVarName, $newListVarName);

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

$newElementVarName = '1';
$newListVarName = '1,2,3';

$newListTwo = implode(',', array_filter(explode(',', $newListVarName), function ($item) use ($newElementVarName) {
    return $newElementVarName == $item;
}));

?>
