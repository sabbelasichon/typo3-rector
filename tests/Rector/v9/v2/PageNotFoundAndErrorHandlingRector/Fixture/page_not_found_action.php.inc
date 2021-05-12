<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v2\PageNotFoundAndErrorHandlingRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class SomeController extends ActionController
{
    /**
     * @var TypoScriptFrontendController
     */
    protected $typoScriptFrontendController;

    public function __construct(TypoScriptFrontendController $typoScriptFrontendController = null)
    {
        $this->typoScriptFrontendController = $typoScriptFrontendController ?: $GLOBALS['TSFE'];
    }

    public function errorAction(): void
    {
        $message = 'No entry found.';
        $GLOBALS['TSFE']->pageNotFoundAndExit();
    }

    public function unavailableAction(): void
    {
        $message = 'No entry found.';
        $GLOBALS['TSFE']->pageUnavailableAndExit($message);
    }

    protected function setPageNotFound()
    {
        $this->typoScriptFrontendController->pageNotFoundAndExit(
            'No cache configuration found'
        );
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v2\PageNotFoundAndErrorHandlingRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\ErrorController;
use TYPO3\CMS\Core\Http\ImmediateResponseException;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class SomeController extends ActionController
{
    /**
     * @var TypoScriptFrontendController
     */
    protected $typoScriptFrontendController;

    public function __construct(TypoScriptFrontendController $typoScriptFrontendController = null)
    {
        $this->typoScriptFrontendController = $typoScriptFrontendController ?: $GLOBALS['TSFE'];
    }

    public function errorAction(): void
    {
        $message = 'No entry found.';
        $response = GeneralUtility::makeInstance(ErrorController::class)->pageNotFoundAction($GLOBALS['TYPO3_REQUEST']);
        throw new ImmediateResponseException($response);
    }

    public function unavailableAction(): void
    {
        $message = 'No entry found.';
        $response = GeneralUtility::makeInstance(ErrorController::class)->unavailableAction($GLOBALS['TYPO3_REQUEST'], $message);
        throw new ImmediateResponseException($response);
    }

    protected function setPageNotFound()
    {
        $response = GeneralUtility::makeInstance(ErrorController::class)->pageNotFoundAction($GLOBALS['TYPO3_REQUEST'], 'No cache configuration found');
        throw new ImmediateResponseException($response);
    }
}

?>
