<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateTypoScriptFrontendControllerAddCacheTagsAndGetPageCacheTagsRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass
{
    public function foo(): void
    {
        $GLOBALS['TSFE']->addCacheTags([
            'tx_myextension_mytable_123',
            'tx_myextension_mytable_456'
        ]);
    }

    public function bar(): void
    {
        $tsfe = new TypoScriptFrontendController();
        $tsfe->addCacheTags([
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
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass
{
    public function foo(): void
    {
        $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.cache.collector')->addCacheTags(new CacheTag('tx_myextension_mytable_123', 3600), new CacheTag('tx_myextension_mytable_456', 3600));
    }

    public function bar(): void
    {
        $tsfe = new TypoScriptFrontendController();
        $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.cache.collector')->addCacheTags(new CacheTag('tx_myextension_mytable_123', 3600), new CacheTag('tx_myextension_mytable_456', 3600));
    }
}

?>
