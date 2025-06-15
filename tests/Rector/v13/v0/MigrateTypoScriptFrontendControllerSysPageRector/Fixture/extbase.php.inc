<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerSysPageRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass
{
    public function myMethod(): void
    {
        $sys_page = $GLOBALS['TSFE']->sys_page;
        $page = $GLOBALS['TSFE']->sys_page->getFirstWebPage(2);
        $GLOBALS['TSFE']->sys_page->enableFields('table');
    }

    public function myActionWithTypoScriptFrontendController(): void
    {
        $id = $this->getTypoScriptFrontendController()->sys_page;
        $rootLine = $this->getTypoScriptFrontendController()->sys_page->getFirstWebPage(2);
        $this->getTypoScriptFrontendController()->sys_page->enableFields('table');
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerSysPageRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass
{
    public function myMethod(): void
    {
        $sys_page = GeneralUtility::makeInstance(PageRepository::class);
        $page = GeneralUtility::makeInstance(PageRepository::class)->getFirstWebPage(2);
        GeneralUtility::makeInstance(PageRepository::class)->enableFields('table');
    }

    public function myActionWithTypoScriptFrontendController(): void
    {
        $id = GeneralUtility::makeInstance(PageRepository::class);
        $rootLine = GeneralUtility::makeInstance(PageRepository::class)->getFirstWebPage(2);
        GeneralUtility::makeInstance(PageRepository::class)->enableFields('table');
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
