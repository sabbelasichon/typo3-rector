<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\MigrateGeneralUtilityGetIndpEnvRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyExtbaseController extends ActionController
{
    public function myAction(): void
    {
        $siteUrl = GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
        $host = GeneralUtility::getIndpEnv('HTTP_HOST');
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\MigrateGeneralUtilityGetIndpEnvRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyExtbaseController extends ActionController
{
    public function myAction(): void
    {
        $siteUrl = $this->request->getAttribute('normalizedParams')->getSiteUrl();
        $host = $this->request->getAttribute('normalizedParams')->getHttpHost();
    }
}

?>
