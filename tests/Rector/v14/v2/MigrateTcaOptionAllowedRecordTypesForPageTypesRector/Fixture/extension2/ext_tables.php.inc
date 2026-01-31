<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateTcaOptionAllowedRecordTypesForPageTypesRector\Fixture;

use TYPO3\CMS\Core\DataHandling\PageDoktypeRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$dokTypeRegistry = GeneralUtility::makeInstance(PageDoktypeRegistry::class);
$dokTypeRegistry->add(
    116,
    [
        'allowedTables' => 'pages, tt_content',
    ],
);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateTcaOptionAllowedRecordTypesForPageTypesRector\Fixture;

use TYPO3\CMS\Core\DataHandling\PageDoktypeRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$dokTypeRegistry = GeneralUtility::makeInstance(PageDoktypeRegistry::class);
?>
