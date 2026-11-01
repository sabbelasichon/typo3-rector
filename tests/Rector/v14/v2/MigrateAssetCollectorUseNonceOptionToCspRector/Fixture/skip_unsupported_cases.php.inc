<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateAssetCollectorUseNonceOptionToCspRector\Fixture;

use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$assetCollector = GeneralUtility::makeInstance(AssetCollector::class);
$assetCollector->addJavaScript('csp-exists', 'foo.js', [], ['useNonce' => true, 'csp' => true]);
$assetCollector->addJavaScript('no-options', 'foo.js', ['useNonce' => true]);
$assetCollector->addJavaScript('no-use-nonce', 'foo.js', [], ['priority' => true]);
$options = ['useNonce' => true];
$assetCollector->addStyleSheet('options-variable', 'foo.css', [], $options);
