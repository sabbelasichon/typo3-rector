<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveUpdateRootlineDataRector\Fixture;

use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$templateService = GeneralUtility::makeInstance(TemplateService::class);
$templateService->updateRootlineData();

GeneralUtility::makeInstance(TemplateService::class)->updateRootlineData();

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveUpdateRootlineDataRector\Fixture;

use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$templateService = GeneralUtility::makeInstance(TemplateService::class);

?>
