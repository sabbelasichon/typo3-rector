<?php

$GLOBALS['TSFE']->processOutput();
$foo = 'bar';

?>
-----
<?php

$GLOBALS['TSFE']->applyHttpHeadersToResponse();
$GLOBALS['TSFE']->processContentForOutput();
$foo = 'bar';

?>
