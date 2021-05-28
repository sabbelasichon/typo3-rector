<?php

$GLOBALS['TSFE']->processOutput();
$foo = 'bar';

?>
-----
<?php

use TYPO3\CMS\Core\Http\Response;
$GLOBALS['TSFE']->applyHttpHeadersToResponse(new Response());
$GLOBALS['TSFE']->processContentForOutput();
$foo = 'bar';

?>
