<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::getUrl('https://typo3.org', 1, ['Content-Language: de-DE']);
GeneralUtility::getUrl('https://typo3.org', 1, ['Content-Language: de-DE', 'Pragma: no-cache']);
GeneralUtility::getUrl('https://typo3.org');

$headers = ['Content-Language: de-DE', 'Pragma: no-cache'];
GeneralUtility::getUrl('https://typo3.org', 0, $headers);

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::getUrl('https://typo3.org', 1, ['Content-Language' => 'de-DE']);
GeneralUtility::getUrl('https://typo3.org', 1, ['Content-Language' => 'de-DE', 'Pragma' => 'no-cache']);
GeneralUtility::getUrl('https://typo3.org');

$headers = ['Content-Language' => 'de-DE', 'Pragma' => 'no-cache'];
GeneralUtility::getUrl('https://typo3.org', 0, $headers);

?>
