<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\ChangeLocalizationSystemArchitectureRector\Fixture;

use TYPO3\CMS\Core\Localization\LocalizationFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$localizationFactory = GeneralUtility::makeInstance(LocalizationFactory::class);
$fileReference = null;
$languageKey = null;
$data = $localizationFactory->getParsedData($fileReference, $languageKey, null, null, false);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\ChangeLocalizationSystemArchitectureRector\Fixture;

use TYPO3\CMS\Core\Localization\LocalizationFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$localizationFactory = GeneralUtility::makeInstance(LocalizationFactory::class);
$fileReference = null;
$languageKey = null;
$data = $localizationFactory->getParsedData($fileReference, $languageKey);

?>
