<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\UsePhpNativeStringFunctionsRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

GeneralUtility::isFirstPartOfStr('example String', 'example');
StringUtility::beginsWith('example String', 'example');
StringUtility::endsWith('example String', 'example');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\UsePhpNativeStringFunctionsRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

\str_starts_with('example String', 'example');
\str_starts_with('example String', 'example');
\str_ends_with('example String', 'example');

?>
