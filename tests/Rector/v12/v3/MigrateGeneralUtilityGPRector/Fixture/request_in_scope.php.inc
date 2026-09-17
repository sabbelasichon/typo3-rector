<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v3\MigrateGeneralUtilityGPRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $value = GeneralUtility::_GP('tx_scheduler');
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v3\MigrateGeneralUtilityGPRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $value = $request->getParsedBody()['tx_scheduler'] ?? $request->getQueryParams()['tx_scheduler'] ?? null;
    }
}
?>
