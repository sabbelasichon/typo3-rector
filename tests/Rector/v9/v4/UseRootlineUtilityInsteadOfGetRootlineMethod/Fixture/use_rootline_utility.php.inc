<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseRootlineUtilityInsteadOfGetRootlineMethod\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$rootline = $GLOBALS['TSFE']->sys_page->getRootLine(1);

class MyClass
{
    protected $frontendController;

    public function __construct(TypoScriptFrontendController $frontendController)
    {
        $this->frontendController = $frontendController;
    }

    /**
     * @return TypoScriptFrontendController
     */
    public function getFrontendController(): TypoScriptFrontendController
    {
        return $this->frontendController;
    }
}

$myClass = new MyClass(new TypoScriptFrontendController());
$id = 2;
$rootline = $myClass->getFrontendController()->sys_page->getRootLine($id, '1-2');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseRootlineUtilityInsteadOfGetRootlineMethod\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$rootline = GeneralUtility::makeInstance(RootlineUtility::class, 1, '')->get();

class MyClass
{
    protected $frontendController;

    public function __construct(TypoScriptFrontendController $frontendController)
    {
        $this->frontendController = $frontendController;
    }

    /**
     * @return TypoScriptFrontendController
     */
    public function getFrontendController(): TypoScriptFrontendController
    {
        return $this->frontendController;
    }
}

$myClass = new MyClass(new TypoScriptFrontendController());
$id = 2;
$rootline = GeneralUtility::makeInstance(RootlineUtility::class, $id, '1-2')->get();

?>
