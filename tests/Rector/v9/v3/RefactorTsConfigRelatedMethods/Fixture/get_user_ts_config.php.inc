<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v3\RefactorTsConfigRelatedMethods\Fixture;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

class SC_alt_db_navframe
{
    function init()
    {
        $tsConfig = $this->getBackendUser()->getTSConfig();
        $tsConfigSubArray = $this->getBackendUser()->getTSConfig('options.someToggle');
        $value = (array)$this->getBackendUser()->getTSConfigProp('options.somePartWithSubToggles');
        $hasFilterBox = !$GLOBALS['BE_USER']->getTSConfigVal('options.pageTree.hideFilter');
        $showPageWithTitle = $this->getBackendUser()->getTSConfigVal('options.pageTree.showPageIdWithTitle');
    }

    function notResolvable($option)
    {
        $tsConfigSubArray = $this->getBackendUser()->getTSConfig($option);
    }

    private function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v3\RefactorTsConfigRelatedMethods\Fixture;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

class SC_alt_db_navframe
{
    function init()
    {
        $tsConfig = $this->getBackendUser()->getTSConfig();
        $tsConfigSubArray = $this->getBackendUser()->getTSConfig()['options.']['someToggle.'] ?? null;
        $value = (array)($this->getBackendUser()->getTSConfig()['options.']['somePartWithSubToggles.'] ?? []);
        $hasFilterBox = !($GLOBALS['BE_USER']->getTSConfig()['options.']['pageTree.']['hideFilter.'] ?? null);
        $showPageWithTitle = $this->getBackendUser()->getTSConfig()['options.']['pageTree.']['showPageIdWithTitle.'] ?? null;
    }

    function notResolvable($option)
    {
        $tsConfigSubArray = $this->getBackendUser()->getTSConfig($option);
    }

    private function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}

?>
