<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\UseServerRequestInsteadOfGeneralUtilityGetRector\Fixture;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class MyController extends ActionController
{
    public function myAction(): ResponseInterface
    {
        $value = GeneralUtility::_GET('tx_scheduler');

        $ll = LocalizationUtility::translate('foo', 'MyExtension') ?? '';
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\UseServerRequestInsteadOfGeneralUtilityGetRector\Fixture;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class MyController extends ActionController
{
    public function myAction(): ResponseInterface
    {
        $value = $this->request->getQueryParams()['tx_scheduler'] ?? null;

        $ll = LocalizationUtility::translate('foo', 'MyExtension') ?? '';
    }
}

?>
