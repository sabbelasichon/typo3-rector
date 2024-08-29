<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\DateTimeAspectInsteadOfGlobalsExecTimeRector\Fixture;

class MyClass
{
    /**
     * @var int
     */
    private $currentTimestamp;

    /**
     * @var int
     */
    private $simulatedTimestamp;

    /**
     * @var int
     */
    private $accessTimestamp;

    /**
     * @var int
     */
    private $simulatedTimestampAccessTimestamp;

    public function __construct()
    {
        $this->currentTimestamp = $GLOBALS['EXEC_TIME'];
        $this->simulatedTimestamp = $GLOBALS['SIM_EXEC_TIME'];
        $this->accessTimestamp = $GLOBALS['ACCESS_TIME'];
        $this->simulatedTimestampAccessTimestamp = $GLOBALS['ACCESS_TIME'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\DateTimeAspectInsteadOfGlobalsExecTimeRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;

class MyClass
{
    /**
     * @var int
     */
    private $currentTimestamp;

    /**
     * @var int
     */
    private $simulatedTimestamp;

    /**
     * @var int
     */
    private $accessTimestamp;

    /**
     * @var int
     */
    private $simulatedTimestampAccessTimestamp;

    public function __construct()
    {
        $this->currentTimestamp = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('date', 'timestamp');
        $this->simulatedTimestamp = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('date', 'timestamp');
        $this->accessTimestamp = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('date', 'timestamp');
        $this->simulatedTimestampAccessTimestamp = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('date', 'timestamp');
    }
}

?>
