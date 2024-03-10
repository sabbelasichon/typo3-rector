<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v0\ForceTemplateParsingInTsfeAndTemplateServiceRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class ForceTemplateParsingInTsfeAndTemplateServiceTestClass
{
    protected function testWithGlobals(): void
    {
        $myvariable = $GLOBALS['TSFE']->forceTemplateParsing;
        $myvariable2 = $GLOBALS['TSFE']->tmpl->forceTemplateParsing;

        $GLOBALS['TSFE']->forceTemplateParsing = true;
        $GLOBALS['TSFE']->tmpl->forceTemplateParsing = true;
        $sesCategory = $GLOBALS['TSFE']->fe_user->getKey('ses', 'category');
    }

    public function testWithReference(): void
    {
        $myvariable3 = $this->getTypoScriptFrontendController()->forceTemplateParsing;
        $myvariable4 = $this->getTypoScriptFrontendController()->tmpl->forceTemplateParsing;

        $this->getTypoScriptFrontendController()->forceTemplateParsing = true;
        $this->getTypoScriptFrontendController()->tmpl->forceTemplateParsing = true;
        $sesCategory = $this->getTypoScriptFrontendController()->fe_user->getKey('ses', 'category');
    }

    public function doNotTouchThis(): void
    {
        $myvariable = $GLOBALS['TYPO3_REQUEST']->forceTemplateParsing;
        $myvariable2 = $GLOBALS['TYPO3_REQUEST']->tmpl->forceTemplateParsing;

        $GLOBALS['TYPO3_REQUEST']->forceTemplateParsing = true;
        $GLOBALS['TYPO3_REQUEST']->tmpl->forceTemplateParsing = true;
        $sesCategory = $GLOBALS['TYPO3_REQUEST']->fe_user->getKey('ses', 'category');
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v0\ForceTemplateParsingInTsfeAndTemplateServiceRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\TypoScriptAspect;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class ForceTemplateParsingInTsfeAndTemplateServiceTestClass
{
    protected function testWithGlobals(): void
    {
        $myvariable = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');
        $myvariable2 = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');

        GeneralUtility::makeInstance(Context::class)->setAspect('typoscript', GeneralUtility::makeInstance(TypoScriptAspect::class, true));
        GeneralUtility::makeInstance(Context::class)->setAspect('typoscript', GeneralUtility::makeInstance(TypoScriptAspect::class, true));
        $sesCategory = $GLOBALS['TSFE']->fe_user->getKey('ses', 'category');
    }

    public function testWithReference(): void
    {
        $myvariable3 = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');
        $myvariable4 = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');

        GeneralUtility::makeInstance(Context::class)->setAspect('typoscript', GeneralUtility::makeInstance(TypoScriptAspect::class, true));
        GeneralUtility::makeInstance(Context::class)->setAspect('typoscript', GeneralUtility::makeInstance(TypoScriptAspect::class, true));
        $sesCategory = $this->getTypoScriptFrontendController()->fe_user->getKey('ses', 'category');
    }

    public function doNotTouchThis(): void
    {
        $myvariable = $GLOBALS['TYPO3_REQUEST']->forceTemplateParsing;
        $myvariable2 = $GLOBALS['TYPO3_REQUEST']->tmpl->forceTemplateParsing;

        $GLOBALS['TYPO3_REQUEST']->forceTemplateParsing = true;
        $GLOBALS['TYPO3_REQUEST']->tmpl->forceTemplateParsing = true;
        $sesCategory = $GLOBALS['TYPO3_REQUEST']->fe_user->getKey('ses', 'category');
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
?>
