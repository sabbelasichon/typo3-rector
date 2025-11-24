<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveConcatenateAndCompressHandlerRector\Fixture;

$GLOBALS['TYPO3_CONF_VARS']['FE']['cssConcatenateHandler'] = 'TYPO3\CMS\Core\Resource\ResourceCompressor';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cssCompressHandler'] = 'TYPO3\CMS\Core\Resource\ResourceCompressor';
$GLOBALS['TYPO3_CONF_VARS']['FE']['jsConcatenateHandler'] = 'TYPO3\CMS\Core\Resource\ResourceCompressor';
$GLOBALS['TYPO3_CONF_VARS']['FE']['jsCompressHandler'] = 'TYPO3\CMS\Core\Resource\ResourceCompressor';
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveConcatenateAndCompressHandlerRector\Fixture;

?>
