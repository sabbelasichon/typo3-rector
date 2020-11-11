<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseLanguageAspectForTsfeLanguageProperties\Fixture;

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

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseLanguageAspectForTsfeLanguageProperties\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$languageId = GeneralUtility::makeInstance(Context::class)->getAspect('language')->getId();
$languageContent = GeneralUtility::makeInstance(Context::class)->getAspect('language')->getContentId();
$languageContentOl = GeneralUtility::makeInstance(Context::class)->getAspect('language')->getLegacyOverlayType();
$languageMode = GeneralUtility::makeInstance(Context::class)->getAspect('language')->getLegacyLanguageMode();

/** @var TypoScriptFrontendController $typoScriptFrontendController */
$typoScriptFrontendController = $GLOBALS['TSFE'];
$languageContentOl = GeneralUtility::makeInstance(Context::class)->getAspect('language')->getLegacyOverlayType();

class MyClass
{
    public function __construct()
    {
        $languageId = GeneralUtility::makeInstance(Context::class)->getAspect('language')->getId();
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
