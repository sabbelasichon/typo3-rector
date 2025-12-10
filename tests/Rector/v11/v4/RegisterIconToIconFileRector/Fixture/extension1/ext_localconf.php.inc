<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v4\RegisterIconToIconFileRector\Fixture;

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
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
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v4\RegisterIconToIconFileRector\Fixture;

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
?>
