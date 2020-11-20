<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\RefactorBackendUtilityGetPagesTSconfig\Fixture;

use TYPO3\CMS\Backend\Utility\BackendUtility;

class MyClass
{
    /**
     * @var array|null
     */
    private $rootline;

    public function getPagesTsConfigWrapper($id, $rootLine = null, $returnPartArray = false)
    {
        BackendUtility::getPagesTSconfig($id, $rootLine, $returnPartArray);
    }

    public function getPagesTsConfigWrapper2($id, $returnPartArray = false)
    {
        BackendUtility::getPagesTSconfig($id, $this->rootline, $returnPartArray);
    }
}

$id = 1;
BackendUtility::getPagesTSconfig($id);
BackendUtility::getPagesTSconfig($id, null, false);
$returnPartArray = true;
BackendUtility::getPagesTSconfig($id, null, $returnPartArray);
BackendUtility::getPagesTSconfig($id, ['rootline'], true);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\RefactorBackendUtilityGetPagesTSconfig\Fixture;

use TYPO3\CMS\Backend\Utility\BackendUtility;

class MyClass
{
    /**
     * @var array|null
     */
    private $rootline;

    public function getPagesTsConfigWrapper($id, $rootLine = null, $returnPartArray = false)
    {
        BackendUtility::getPagesTSconfig($id, $rootLine, $returnPartArray);
    }

    public function getPagesTsConfigWrapper2($id, $returnPartArray = false)
    {
        BackendUtility::getPagesTSconfig($id, $this->rootline, $returnPartArray);
    }
}

$id = 1;
BackendUtility::getPagesTSconfig($id);
BackendUtility::getPagesTSconfig($id);
$returnPartArray = true;
BackendUtility::getRawPagesTSconfig($id);
BackendUtility::getPagesTSconfig($id, ['rootline'], true);

?>
