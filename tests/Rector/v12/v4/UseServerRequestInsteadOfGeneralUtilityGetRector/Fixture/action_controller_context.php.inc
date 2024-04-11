<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\UseServerRequestInsteadOfGeneralUtilityGetRector\Fixture;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myAction(): ResponseInterface
    {
        $value = GeneralUtility::_GET('tx_scheduler');
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\UseServerRequestInsteadOfGeneralUtilityGetRector\Fixture;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myAction(): ResponseInterface
    {
        $value = $this->request->getQueryParams()['tx_scheduler'];
    }
}

?>
