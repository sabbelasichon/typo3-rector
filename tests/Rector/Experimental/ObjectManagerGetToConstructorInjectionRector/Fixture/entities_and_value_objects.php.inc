<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Experimental\ObjectManagerGetToConstructorInjectionRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\Experimental\ObjectManagerGetToConstructorInjectionRector\Source\MyEntity;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

final class MyService
{
    private ObjectManagerInterface $objectManager;

    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    protected function execute()
    {
        $myEntity = $this->objectManager->get(MyEntity::class);
    }
}

?>
