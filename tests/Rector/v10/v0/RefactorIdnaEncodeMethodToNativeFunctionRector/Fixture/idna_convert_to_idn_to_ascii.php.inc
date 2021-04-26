<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$domain = GeneralUtility::idnaEncode('domain.com');
$email = GeneralUtility::idnaEncode('email@domain.com');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$domain = idn_to_ascii('domain.com', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
$email = 'email@' . idn_to_ascii('domain.com', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);

?>
