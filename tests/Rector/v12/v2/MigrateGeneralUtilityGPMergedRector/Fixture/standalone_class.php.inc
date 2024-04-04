<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v2\MigrateGeneralUtilityGPMergedRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    private static $staticSchedulerData = [];
    private array $propertySchedulerData = [];
    private string $prefix = 'tx_scheduler';

    public function myMethod(): void
    {
        $variableSchedulerData = GeneralUtility::_GPmerged('tx_scheduler');

        self::$staticSchedulerData = GeneralUtility::_GPmerged('tx_scheduler');

        $this->propertySchedulerData = GeneralUtility::_GPmerged($this->prefix);

        if (GeneralUtility::_GPmerged('tx_scheduler') === []) {
            // too complex to migrate
        }

        // invalid code
        $anotherValue = GeneralUtility::_GPmerged(null);
        $anotherValue2 = GeneralUtility::_GPmerged();

        // ignore
        $anotherValue = GeneralUtility::_GP('tx_scheduler');
    }

    protected function getPluginParameters()
    {
        // This is an edge case we don't handle. Use an assignment instead and then we can migrate the code!
        return GeneralUtility::_GPmerged($this->prefix);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v2\MigrateGeneralUtilityGPMergedRector\Fixture;

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    private static $staticSchedulerData = [];
    private array $propertySchedulerData = [];
    private string $prefix = 'tx_scheduler';

    public function myMethod(): void
    {
        $variableSchedulerData = $GLOBALS['TYPO3_REQUEST']->getQueryParams()['tx_scheduler'];
        ArrayUtility::mergeRecursiveWithOverrule($variableSchedulerData, $GLOBALS['TYPO3_REQUEST']->getParsedBody()['tx_scheduler']);

        self::$staticSchedulerData = $GLOBALS['TYPO3_REQUEST']->getQueryParams()['tx_scheduler'];
        ArrayUtility::mergeRecursiveWithOverrule(self::$staticSchedulerData, $GLOBALS['TYPO3_REQUEST']->getParsedBody()['tx_scheduler']);

        $this->propertySchedulerData = $GLOBALS['TYPO3_REQUEST']->getQueryParams()[$this->prefix];
        ArrayUtility::mergeRecursiveWithOverrule($this->propertySchedulerData, $GLOBALS['TYPO3_REQUEST']->getParsedBody()[$this->prefix]);

        if (GeneralUtility::_GPmerged('tx_scheduler') === []) {
            // too complex to migrate
        }

        // invalid code
        $anotherValue = GeneralUtility::_GPmerged(null);
        $anotherValue2 = GeneralUtility::_GPmerged();

        // ignore
        $anotherValue = GeneralUtility::_GP('tx_scheduler');
    }

    protected function getPluginParameters()
    {
        // This is an edge case we don't handle. Use an assignment instead and then we can migrate the code!
        return GeneralUtility::_GPmerged($this->prefix);
    }
}

?>
