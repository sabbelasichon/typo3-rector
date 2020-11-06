<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v0\GetPreferredClientLanguage\Fixture;

use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FooBarBaz
{
    public function __construct()
    {
        $preferredLanguage = GeneralUtility::makeInstance(CharsetConverter::class)->getPreferredClientLanguage(GeneralUtility::getIndpEnv('HTTP_ACCEPT_LANGUAGE'));
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v0\GetPreferredClientLanguage\Fixture;

use TYPO3\CMS\Core\Localization\Locales;
use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FooBarBaz
{
    public function __construct()
    {
        $preferredLanguage = GeneralUtility::makeInstance(Locales::class)->getPreferredClientLanguage(GeneralUtility::getIndpEnv('HTTP_ACCEPT_LANGUAGE'));
    }
}

?>
