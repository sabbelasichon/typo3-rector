<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerSysPageRector\Fixture;

use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class IgnoreAssignment
{
    public function myMethod(): void
    {
        $GLOBALS['TSFE']->sys_page = GeneralUtility::makeInstance(PageRepository::class);
    }

    public function myActionWithTypoScriptFrontendController(): void
    {
        $this->getTypoScriptFrontendController()->sys_page = GeneralUtility::makeInstance(PageRepository::class);
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
