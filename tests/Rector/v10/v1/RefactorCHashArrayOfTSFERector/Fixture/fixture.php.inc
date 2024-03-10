<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\RefactorCHashArrayOfTSFERector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class CanonicalParametersGetDataHook
{
    public function getDataExtension()
    {
        $cHash_array = $GLOBALS['TSFE']->cHash_array;
        unset($cHash_array['encryptionKey']);

        $pageUid = $GLOBALS['TSFE']->id;

        return GeneralUtility::implodeArrayForUrl('', $cHash_array);
    }

    public function getDataExtension2()
    {
        $cHash_array = $GLOBALS['TSFE']->cHash_array;
        unset($cHash_array['encryptionKey']);

        return GeneralUtility::implodeArrayForUrl('', $cHash_array);
    }

    public function doNotTouchThis()
    {
        $cHash_array = $GLOBALS['TYPO3_REQUEST']->cHash_array;
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\RefactorCHashArrayOfTSFERector\Fixture;

use TYPO3\CMS\Frontend\Page\CacheHashCalculator;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

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

        $pageUid = $GLOBALS['TSFE']->id;

        return GeneralUtility::implodeArrayForUrl('', $cHash_array);
    }

    public function getDataExtension2()
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

        return GeneralUtility::implodeArrayForUrl('', $cHash_array);
    }

    public function doNotTouchThis()
    {
        $cHash_array = $GLOBALS['TYPO3_REQUEST']->cHash_array;
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
