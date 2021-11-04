<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\ReplaceTSFEATagParamsCallOnGlobalsRector\Fixture;

$foo = $GLOBALS['TSFE']->ATagParams;

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\ReplaceTSFEATagParamsCallOnGlobalsRector\Fixture;

$foo = $GLOBALS['TSFE']->config['config']['ATagParams'] ?? '';

?>
