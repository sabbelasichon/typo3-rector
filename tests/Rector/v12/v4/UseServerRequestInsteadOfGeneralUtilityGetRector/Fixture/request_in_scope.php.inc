<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\UseServerRequestInsteadOfGeneralUtilityGetRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $value = GeneralUtility::_GET('tx_scheduler');
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\UseServerRequestInsteadOfGeneralUtilityGetRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $value = $request->getQueryParams()['tx_scheduler'] ?? null;
    }
}
?>
