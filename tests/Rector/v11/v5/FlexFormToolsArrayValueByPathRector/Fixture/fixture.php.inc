<?php

use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;

$flexFormTools = new FlexFormTools();
$searchArray = [];
$value = $flexFormTools->getArrayValueByPath('search/path', $searchArray);

$flexFormTools->setArrayValueByPath('set/path', $dataArray, $value);

$flexFormTools->cleanFlexFormXML('tt_content', 'pi_flexform', $row);
?>
-----
<?php

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;

$flexFormTools = new FlexFormTools();
$searchArray = [];
$value = ArrayUtility::getValueByPath($searchArray, 'search/path');

$dataArray = ArrayUtility::setValueByPath($dataArray, 'set/path', $value);

$flexFormTools->cleanFlexFormXML('tt_content', 'pi_flexform', $row);
?>
