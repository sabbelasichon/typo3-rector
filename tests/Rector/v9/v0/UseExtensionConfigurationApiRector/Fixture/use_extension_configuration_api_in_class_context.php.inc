<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\UseExtensionConfigurationApiRector\Fixture;

class BackendUtilitySlot
{
    /**
     * @var string
     */
    protected $extKey = '';

    public function getPagesTsConfigPreInclude($typoscriptDataArray, $pageUid, $rootLine, $returnPartArray)
    {
        $pageUid = (int)$pageUid;

        if ($pageUid === 0) {
            return null;
        }

        if (empty($this->extKey)) {
            return null;
        }

        $extConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\UseExtensionConfigurationApiRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
class BackendUtilitySlot
{
    /**
     * @var string
     */
    protected $extKey = '';

    public function getPagesTsConfigPreInclude($typoscriptDataArray, $pageUid, $rootLine, $returnPartArray)
    {
        $pageUid = (int)$pageUid;

        if ($pageUid === 0) {
            return null;
        }

        if (empty($this->extKey)) {
            return null;
        }

        $extConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get($this->extKey);
    }
}

?>
