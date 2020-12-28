<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\TypeHandlingService;

$typeHandlingService = GeneralUtility::makeInstance(TypeHandlingService::class);
$typeHandlingService->parseType('string');
$typeHandlingService->normalizeType('string');
$typeHandlingService->isLiteral('string');
$typeHandlingService->isSimpleType('string');

?>
-----
<?php

use TYPO3\CMS\Extbase\Utility\TypeHandlingUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\TypeHandlingService;

$typeHandlingService = GeneralUtility::makeInstance(TypeHandlingService::class);
TypeHandlingUtility::parseType('string');
TypeHandlingUtility::normalizeType('string');
TypeHandlingUtility::isLiteral('string');
TypeHandlingUtility::isSimpleType('string');

?>
