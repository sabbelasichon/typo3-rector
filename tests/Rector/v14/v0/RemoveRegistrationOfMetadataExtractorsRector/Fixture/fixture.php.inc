<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveRegistrationOfMetadataExtractorsRector\Fixture;

use TYPO3\CMS\Core\Resource\Index\ExtractorRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$extractorRegistry = GeneralUtility::makeInstance(ExtractorRegistry::class);
$extractorRegistry->registerExtractionService('MyExtractor');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveRegistrationOfMetadataExtractorsRector\Fixture;

use TYPO3\CMS\Core\Resource\Index\ExtractorRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$extractorRegistry = GeneralUtility::makeInstance(ExtractorRegistry::class);

?>
