<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateTypoScriptFrontendControllerAddCacheTagsAndGetPageCacheTagsRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyClass extends ActionController
{
    public function foo(): void
    {
        $GLOBALS['TSFE']->addCacheTags([
            'tx_myextension_mytable_123',
            'tx_myextension_mytable_456'
        ]);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateTypoScriptFrontendControllerAddCacheTagsAndGetPageCacheTagsRector\Fixture;

use TYPO3\CMS\Core\Cache\CacheTag;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyClass extends ActionController
{
    public function foo(): void
    {
        $this->request->getAttribute('frontend.cache.collector')->addCacheTags(new CacheTag('tx_myextension_mytable_123', 3600), new CacheTag('tx_myextension_mytable_456', 3600));
    }
}

?>
