<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\AddServerRequestArgumentToGeneralUtilityMethodsRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyMiddleware
{
    public function process(ServerRequestInterface $request, string $url): void
    {
        $isOnCurrentHost = GeneralUtility::isOnCurrentHost($url);
        $sanitizedUrl = GeneralUtility::sanitizeLocalUrl($url);
        $absoluteUrl = GeneralUtility::locationHeaderUrl($url);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\AddServerRequestArgumentToGeneralUtilityMethodsRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyMiddleware
{
    public function process(ServerRequestInterface $request, string $url): void
    {
        $isOnCurrentHost = GeneralUtility::isOnCurrentHost($url, $request);
        $sanitizedUrl = GeneralUtility::sanitizeLocalUrl($url, $request);
        $absoluteUrl = GeneralUtility::locationHeaderUrl($url, $request);
    }
}

?>
