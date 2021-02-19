<?php

use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

class SubstituteObjetManagerInterfaceTemp
{
    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    public function example()
    {
        $service = $this->objectManager->get(Service::class);
    }
}

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

class SubstituteObjetManagerInterfaceTemp
{
    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    public function example()
    {
        $service = GeneralUtility::makeInstance(Service::class);
    }
}

?>
