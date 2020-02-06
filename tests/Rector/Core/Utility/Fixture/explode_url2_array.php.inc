<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$variable = GeneralUtility::explodeUrl2Array('https://www.domain.com', true);
$variable2 = GeneralUtility::explodeUrl2Array('https://www.domain.com', false);

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

parse_str('https://www.domain.com', $variable);
$variable2 = GeneralUtility::explodeUrl2Array('https://www.domain.com');

?>
