<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v2\MigrateGeneralUtilityGPMergedRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myMethod(): void
    {
        $getMergedWithPost = GeneralUtility::_GPmerged('tx_scheduler');
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v2\MigrateGeneralUtilityGPMergedRector\Fixture;

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myMethod(): void
    {
        $getMergedWithPost = $this->request->getQueryParams()['tx_scheduler'];
        ArrayUtility::mergeRecursiveWithOverrule($getMergedWithPost, $this->request->getParsedBody()['tx_scheduler']);
    }
}

?>
