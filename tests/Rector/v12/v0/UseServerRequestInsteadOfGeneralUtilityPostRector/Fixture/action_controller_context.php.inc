<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\UseServerRequestInsteadOfGeneralUtilityPostRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class MyController extends ActionController
{
    public function myMethod(): void
    {
        $value = GeneralUtility::_POST('tx_scheduler');

        $ll = LocalizationUtility::translate('foo', 'MyExtension') ?? '';
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\UseServerRequestInsteadOfGeneralUtilityPostRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class MyController extends ActionController
{
    public function myMethod(): void
    {
        $value = $this->request->getParsedBody()['tx_scheduler'] ?? null;

        $ll = LocalizationUtility::translate('foo', 'MyExtension') ?? '';
    }
}

?>
