<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\FlexFormToolsGetArrayValueByPathRector\Fixture;

use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;

$flexFormTools = new FlexFormTools();
$searchArray = [];
$value = $flexFormTools->getArrayValueByPath('search/path', $searchArray);

$flexFormTools->setArrayValueByPath('set/path', $dataArray, $value);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\FlexFormToolsGetArrayValueByPathRector\Fixture;

use TYPO3\CMS\Core\Utility\ArrayUtility;

$searchArray = [];
$value = ArrayUtility::getValueByPath($searchArray, 'search/path');

$dataArray = ArrayUtility::setValueByPath($dataArray, 'set/path', $value);
?>
