<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseLanguageAspectForTsfeLanguagePropertiesRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$languageId = $GLOBALS['TSFE']->sys_language_uid;
$languageContent = $GLOBALS['TSFE']->sys_language_content;
$languageContentOl = $GLOBALS['TSFE']->sys_language_contentOL;
$languageMode = $GLOBALS['TSFE']->sys_language_mode;

/** @var TypoScriptFrontendController $typoScriptFrontendController */
$typoScriptFrontendController = $GLOBALS['TSFE'];
$languageContentOl = $typoScriptFrontendController->sys_language_contentOL;

class MyClass
{
    public function __construct()
    {
        $languageId = $this->getTsfe()->sys_language_uid;
    }

    /**
     * @return TypoScriptFrontendController
     */
    private function getTsfe()
    {
        return $GLOBALS['TSFE'];
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseLanguageAspectForTsfeLanguagePropertiesRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$languageId = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('language', 'id');
$languageContent = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('language', 'contentId');
$languageContentOl = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('language', 'legacyOverlayType');
$languageMode = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('language', 'legacyLanguageMode');

/** @var TypoScriptFrontendController $typoScriptFrontendController */
$typoScriptFrontendController = $GLOBALS['TSFE'];
$languageContentOl = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('language', 'legacyOverlayType');

class MyClass
{
    public function __construct()
    {
        $languageId = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('language', 'id');
    }

    /**
     * @return TypoScriptFrontendController
     */
    private function getTsfe()
    {
        return $GLOBALS['TSFE'];
    }
}

?>
