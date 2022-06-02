<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

// tslib_cObj
/**
 * tslib_cObj
 */

if (class_exists('tslib_cObj')) {

}

GeneralUtility::makeInstance('tslib_cObj');
GeneralUtility::makeInstance('foo');
$languageId = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('language', 'id');

$foo = [
    'category' => 'template',
];

?>
-----
<?php

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

// tslib_cObj
/**
 * tslib_cObj
 */

if (class_exists(ContentObjectRenderer::class)) {

}

GeneralUtility::makeInstance(ContentObjectRenderer::class);
GeneralUtility::makeInstance('foo');
$languageId = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('language', 'id');

$foo = [
    'category' => 'template',
];

?>
