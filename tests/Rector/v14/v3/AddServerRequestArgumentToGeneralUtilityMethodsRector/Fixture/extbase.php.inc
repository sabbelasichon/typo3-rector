<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\AddServerRequestArgumentToGeneralUtilityMethodsRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyExtbaseController extends ActionController
{
    public function myAction(string $url): void
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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyExtbaseController extends ActionController
{
    public function myAction(string $url): void
    {
        $isOnCurrentHost = GeneralUtility::isOnCurrentHost($url, $this->request);
        $sanitizedUrl = GeneralUtility::sanitizeLocalUrl($url, $this->request);
        $absoluteUrl = GeneralUtility::locationHeaderUrl($url, $this->request);
    }
}

?>
