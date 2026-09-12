<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\MigrateGeneralUtilityGetIndpEnvRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $siteUrl = GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
        $host = GeneralUtility::getIndpEnv('HTTP_HOST');
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\MigrateGeneralUtilityGetIndpEnvRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $siteUrl = $request->getAttribute('normalizedParams')->getSiteUrl();
        $host = $request->getAttribute('normalizedParams')->getHttpHost();
    }
}

?>
