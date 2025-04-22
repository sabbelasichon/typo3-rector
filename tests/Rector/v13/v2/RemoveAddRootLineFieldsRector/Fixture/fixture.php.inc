<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v2\RemoveAddRootLineFieldsRector\Fixture;

$GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] = 'foo';
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v2\RemoveAddRootLineFieldsRector\Fixture;

?>
