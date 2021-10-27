<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Frontend\ContentObject\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

final class GlobalTsfe
{
    public function doSomethingElse(): void
    {
        /** @var TypoScriptFrontendController $typoscriptFrontendController2 */
        $typoscriptFrontendController2 = $GLOBALS['TSFE'];
        $typoscriptFrontendController2->cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Frontend\ContentObject\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

final class GlobalTsfe
{
    public function doSomethingElse(): void
    {
        /** @var TypoScriptFrontendController $typoscriptFrontendController2 */
        $typoscriptFrontendController2 = $GLOBALS['TSFE'];
        $typoscriptFrontendController2->cObj->cObjGetSingle('RECORDS', ['tables' => 'tt_content', 'source' => '1,2,3']);
    }
}

?>
