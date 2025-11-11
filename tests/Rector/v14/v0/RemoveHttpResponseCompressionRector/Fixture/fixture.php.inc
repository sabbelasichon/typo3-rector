<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveHttpResponseCompressionRector\Fixture;

$GLOBALS['TYPO3_CONF_VARS']['FE']['compressionLevel'] = 9;
$GLOBALS['TYPO3_CONF_VARS']['BE']['compressionLevel'] = 9;
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveHttpResponseCompressionRector\Fixture;

$GLOBALS['TYPO3_CONF_VARS']['FE']['compressionLevel'] = 9;
?>
