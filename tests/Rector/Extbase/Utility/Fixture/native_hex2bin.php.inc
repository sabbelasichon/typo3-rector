<?php

use TYPO3\CMS\Extbase\Utility\TypeHandlingUtility;

$hex2bin = TypeHandlingUtility::hex2bin('6578616d706c65206865782064617461');

?>
-----
<?php

use TYPO3\CMS\Extbase\Utility\TypeHandlingUtility;

$hex2bin = hex2bin('6578616d706c65206865782064617461');

?>
