<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v2\MigrateGeneralUtilityGPMergedRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $getMergedWithPost = GeneralUtility::_GPmerged('tx_scheduler');
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v2\MigrateGeneralUtilityGPMergedRector\Fixture;

use TYPO3\CMS\Core\Utility\ArrayUtility;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $getMergedWithPost = $request->getQueryParams()['tx_scheduler'];
        ArrayUtility::mergeRecursiveWithOverrule($getMergedWithPost, $request->getParsedBody()['tx_scheduler']);
    }
}
?>
