<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateTypoScriptFrontendControllerAddCacheTagsAndGetPageCacheTagsRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyClass extends ActionController
{
    public function foo(): void
    {
        $tags = $GLOBALS['TSFE']->getPageCacheTags();
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateTypoScriptFrontendControllerAddCacheTagsAndGetPageCacheTagsRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyClass extends ActionController
{
    public function foo(): void
    {
        $tags = $this->request->getAttribute('frontend.cache.collector')->getCacheTags();
    }
}

?>
