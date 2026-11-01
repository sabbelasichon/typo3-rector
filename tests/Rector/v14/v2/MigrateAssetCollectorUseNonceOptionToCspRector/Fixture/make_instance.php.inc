<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateAssetCollectorUseNonceOptionToCspRector\Fixture;

use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$assetCollector = GeneralUtility::makeInstance(AssetCollector::class);
$assetCollector->addJavaScript('my-script', 'EXT:my_ext/Resources/Public/JavaScript/foo.js', [], ['useNonce' => true]);
$assetCollector->addStyleSheet('my-style', 'EXT:my_ext/Resources/Public/Css/foo.css', [], ['useNonce' => true]);
GeneralUtility::makeInstance(AssetCollector::class)->addJavaScript('other-script', 'EXT:my_ext/Resources/Public/JavaScript/bar.js', ['async' => 'async'], ['useNonce' => true]);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateAssetCollectorUseNonceOptionToCspRector\Fixture;

use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$assetCollector = GeneralUtility::makeInstance(AssetCollector::class);
$assetCollector->addJavaScript('my-script', 'EXT:my_ext/Resources/Public/JavaScript/foo.js', [], ['csp' => true]);
$assetCollector->addStyleSheet('my-style', 'EXT:my_ext/Resources/Public/Css/foo.css', [], ['csp' => true]);
GeneralUtility::makeInstance(AssetCollector::class)->addJavaScript('other-script', 'EXT:my_ext/Resources/Public/JavaScript/bar.js', ['async' => 'async'], ['csp' => true]);

?>
