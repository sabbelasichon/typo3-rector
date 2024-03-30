<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v3\MigrateGeneralUtilityGPRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myMethod(): void
    {
        $value = GeneralUtility::_GP('tx_scheduler');
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v3\MigrateGeneralUtilityGPRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myMethod(): void
    {
        $value = $this->request->getParsedBody()['tx_scheduler'] ?? $this->request->getQueryParams()['tx_scheduler'] ?? null;
    }
}

?>
