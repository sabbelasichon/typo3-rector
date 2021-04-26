<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v3\CopyMethodGetPidForModTSconfigRector\Fixture;

use TYPO3\CMS\Backend\Utility\BackendUtility;

class MyClass
{
    /**
     * @var string
     */
    private $table = 'pages';

    public function __construct()
    {
        $modPid1 = BackendUtility::getPidForModTSconfig($this->table, 1, 2);
    }


}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v3\CopyMethodGetPidForModTSconfigRector\Fixture;

use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

class MyClass
{
    /**
     * @var string
     */
    private $table = 'pages';

    public function __construct()
    {
        $modPid1 = $this->table === 'pages' && MathUtility::canBeInterpretedAsInteger(1) ? 1 : 2;
    }


}

?>
