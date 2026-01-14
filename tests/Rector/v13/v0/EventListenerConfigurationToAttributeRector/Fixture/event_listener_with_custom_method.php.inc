<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\Source\MySpecialEvent;

class MyEventListenerWithCustomMethod
{
    public function specialMethod(MySpecialEvent $event)
    {
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\Fixture;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\Source\MySpecialEvent;

class MyEventListenerWithCustomMethod
{
    #[AsEventListener(identifier: 'my-identifier')]
    public function specialMethod(MySpecialEvent $event)
    {
    }
}

?>
