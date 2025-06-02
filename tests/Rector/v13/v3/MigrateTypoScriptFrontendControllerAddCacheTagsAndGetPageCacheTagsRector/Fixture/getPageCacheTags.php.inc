<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateTypoScriptFrontendControllerAddCacheTagsAndGetPageCacheTagsRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass
{
    public function foo(): void
    {
        $tags = $GLOBALS['TSFE']->getPageCacheTags();
    }

    public function bar(): void
    {
        $tsfe = new TypoScriptFrontendController();
        $tags = $tsfe->getPageCacheTags();
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateTypoScriptFrontendControllerAddCacheTagsAndGetPageCacheTagsRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass
{
    public function foo(): void
    {
        $tags = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.cache.collector')->getCacheTags();
    }

    public function bar(): void
    {
        $tsfe = new TypoScriptFrontendController();
        $tags = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.cache.collector')->getCacheTags();
    }
}

?>
