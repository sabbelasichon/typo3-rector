<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\RefactorInternalPropertiesOfTSFERector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class CanonicalParametersGetDataHook
{
    public function getDataExtension()
    {
        $cHash_array = $GLOBALS['TSFE']->cHash_array;
        unset($cHash_array['encryptionKey']);

        $cHash = $GLOBALS['TSFE']->cHash;

        $domainStartPage = $GLOBALS['TSFE']->domainStartPage;

        $GLOBALS['TSFE']->cHash = 'fooBarBaz';

        return GeneralUtility::implodeArrayForUrl('', $cHash_array);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\RefactorInternalPropertiesOfTSFERector\Fixture;

use TYPO3\CMS\Frontend\Page\CacheHashCalculator;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CanonicalParametersGetDataHook
{
    public function getDataExtension()
    {
        $relevantParametersForCachingFromPageArguments = [];
        $pageArguments = $GLOBALS['REQUEST']->getAttribute('routing');
        $queryParams = $pageArguments->getDynamicArguments();
        if (!empty($queryParams) && ($pageArguments->getArguments()['cHash'] ?? false)) {
            $queryParams['id'] = $pageArguments->getPageId();
            $relevantParametersForCachingFromPageArguments = GeneralUtility::makeInstance(CacheHashCalculator::class)->getRelevantParameters(HttpUtility::buildQueryString($queryParams));
        }
        $cHash_array = $relevantParametersForCachingFromPageArguments;
        unset($cHash_array['encryptionKey']);

        $cHash = $GLOBALS['REQUEST']->getAttribute('routing')->getArguments()['cHash'];

        $domainStartPage = $GLOBALS['REQUEST']->getAttribute('site')->getRootPageId();

        $GLOBALS['TSFE']->cHash = 'fooBarBaz';

        return GeneralUtility::implodeArrayForUrl('', $cHash_array);
    }
}

?>
