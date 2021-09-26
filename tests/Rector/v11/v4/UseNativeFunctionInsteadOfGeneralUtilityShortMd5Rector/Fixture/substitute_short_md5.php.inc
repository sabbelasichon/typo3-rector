<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v4\UseNativeFunctionInsteadOfGeneralUtilityShortMd5Rector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

$length = 10;
$input = 'value';

$shortMd5 = GeneralUtility::shortMD5($input, $length);
$shortMd5WithDefaultValue = GeneralUtility::shortMD5($input);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v4\UseNativeFunctionInsteadOfGeneralUtilityShortMd5Rector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

$length = 10;
$input = 'value';

$shortMd5 = substr(md5($input), 0, $length);
$shortMd5WithDefaultValue = substr(md5($input), 0, 10);

?>
