<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v2\PageNotFoundAndErrorHandlingRector\Fixture;

abstract class AbstractPage
{
    abstract public function main();

    protected function showError($msg = null): void
    {
        if ($msg === null) {
            $msg = 'Page is not available, please check your configuration';
        }

        header('HTTP/1.0 503 Service Unavailable');
        $GLOBALS['TSFE']->pageErrorHandler(true, null, $msg);
        exit;
    }

    protected function showErrorWithEmptyCode($msg = null): void
    {
        if ($msg === null) {
            $msg = 'Page is not available, please check your configuration';
        }

        $code = '';
        header('HTTP/1.0 503 Service Unavailable');
        $GLOBALS['TSFE']->pageErrorHandler($code, null, $msg);
        exit;
    }

    protected function showErrorNotResolvable($code, $msg = null): void
    {
        if ($msg === null) {
            $msg = 'Page is not available, please check your configuration';
        }

        header('HTTP/1.0 503 Service Unavailable');
        $GLOBALS['TSFE']->pageErrorHandler($code, null, $msg);
        exit;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v2\PageNotFoundAndErrorHandlingRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Controller\ErrorPageController;
abstract class AbstractPage
{
    abstract public function main();

    protected function showError($msg = null): void
    {
        if ($msg === null) {
            $msg = 'Page is not available, please check your configuration';
        }

        header('HTTP/1.0 503 Service Unavailable');
        echo GeneralUtility::makeInstance(ErrorPageController::class)->errorAction('Page Not Found', 'The page did not exist or was inaccessible.' . ($msg ? ' Reason: ' . $msg : ''));
        exit;
    }

    protected function showErrorWithEmptyCode($msg = null): void
    {
        if ($msg === null) {
            $msg = 'Page is not available, please check your configuration';
        }

        $code = '';
        header('HTTP/1.0 503 Service Unavailable');
        echo GeneralUtility::makeInstance(ErrorPageController::class)->errorAction('Page Not Found', $msg ? 'Reason: ' . $msg : 'Page cannot be found.');
        exit;
    }

    protected function showErrorNotResolvable($code, $msg = null): void
    {
        if ($msg === null) {
            $msg = 'Page is not available, please check your configuration';
        }

        header('HTTP/1.0 503 Service Unavailable');
        $GLOBALS['TSFE']->pageErrorHandler($code, null, $msg);
        exit;
    }
}

?>
