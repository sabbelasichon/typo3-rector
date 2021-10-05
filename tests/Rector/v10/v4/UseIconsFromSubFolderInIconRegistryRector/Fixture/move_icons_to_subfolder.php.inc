<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v4\UseIconsFromSubFolderInIconRegistryRector\Fixture;

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::makeInstance(IconRegistry::class)->registerIcon(
    'apps-pagetree-reference', SvgIconProvider::class,
            [
                'source' => 'typo3/sysext/core/Resources/Public/Icons/T3Icons/content/content-text.svg',
            ]
);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v4\UseIconsFromSubFolderInIconRegistryRector\Fixture;

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::makeInstance(IconRegistry::class)->registerIcon(
    'apps-pagetree-reference', SvgIconProvider::class,
            ['source' => 'typo3/sysext/core/Resources/Public/Icons/T3Icons/svgs/content/content-text.svg']
);

?>
