<?php

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
$iconRegistry->registerIcon(
    'mybitmapicon',
    BitmapIconProvider::class,
    [
        'source' => 'EXT:my_extension/Resources/Public/Icons/mybitmap.png',
    ]
);
$iconRegistry->registerIcon(
    'mysvgicon',
    SvgIconProvider::class,
    [
        'source' => 'EXT:my_extension/Resources/Public/Icons/mysvg.svg',
    ]
);
$iconRegistry->registerIcon(
    'proof-of-concept',
    \TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class,
    [
        'name'     => 'spinner',
        'spinning' => true
    ]
);
?>
-----
<?php

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
?>
