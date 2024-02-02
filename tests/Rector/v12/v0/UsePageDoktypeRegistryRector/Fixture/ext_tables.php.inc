<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\UsePageDoktypeRegistryRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v12\v0\UsePageDoktypeRegistryRector\Source\PageTypeConstant;

$GLOBALS['PAGES_TYPES'][116] = [
    'type' => 'web',
    'allowedTables' => '*',
];

$GLOBALS['PAGES_TYPES'][PageTypeConstant::DOK_TYPE] = [
    'web',
    'allowedTables' => 'pages,pages_language_overlay,tt_content',
    'onlyAllowedTables' => false
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\UsePageDoktypeRegistryRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\DataHandling\PageDoktypeRegistry;
use Ssch\TYPO3Rector\Tests\Rector\v12\v0\UsePageDoktypeRegistryRector\Source\PageTypeConstant;

GeneralUtility::makeInstance(PageDoktypeRegistry::class)->add(116, [
    'type' => 'web',
    'allowedTables' => '*',
]);

GeneralUtility::makeInstance(PageDoktypeRegistry::class)->add(PageTypeConstant::DOK_TYPE, [
    'web',
    'allowedTables' => 'pages,pages_language_overlay,tt_content',
    'onlyAllowedTables' => false
]);

?>
