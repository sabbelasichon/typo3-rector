<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RegisterExtbaseTypeConvertersAsServicesRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RegisterExtbaseTypeConvertersAsServicesRector\Source\MySpecialTypeConverter;
use Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RegisterExtbaseTypeConvertersAsServicesRector\Source\MySecondSpecialTypeConverter;

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter(
    MySpecialTypeConverter::class
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter(
    MySecondSpecialTypeConverter::class
);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RegisterExtbaseTypeConvertersAsServicesRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RegisterExtbaseTypeConvertersAsServicesRector\Source\MySpecialTypeConverter;
use Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RegisterExtbaseTypeConvertersAsServicesRector\Source\MySecondSpecialTypeConverter;

?>
