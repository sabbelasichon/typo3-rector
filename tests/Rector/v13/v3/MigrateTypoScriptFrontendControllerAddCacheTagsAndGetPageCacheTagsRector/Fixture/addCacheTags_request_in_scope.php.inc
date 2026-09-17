<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateTypoScriptFrontendControllerAddCacheTagsAndGetPageCacheTagsRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $GLOBALS['TSFE']->addCacheTags([
            'tx_myextension_mytable_123',
        ]);
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateTypoScriptFrontendControllerAddCacheTagsAndGetPageCacheTagsRector\Fixture;

use TYPO3\CMS\Core\Cache\CacheTag;
use Psr\Http\Message\ServerRequestInterface;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $request->getAttribute('frontend.cache.collector')->addCacheTags(new CacheTag('tx_myextension_mytable_123', 3600));
    }
}
?>
