<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\UseServerRequestInsteadOfGeneralUtilityPostRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $value = GeneralUtility::_POST('tx_scheduler');
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\UseServerRequestInsteadOfGeneralUtilityPostRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $value = $request->getParsedBody()['tx_scheduler'] ?? null;
    }
}
?>
