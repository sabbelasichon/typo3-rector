<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\Source\MySpecialEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;

class MyEventListenerWithAttribute
{
    public function specialMethod(MySpecialEvent $event)
    {

    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\Source\MySpecialEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;

#[AsEventListener(before: 'before', after: 'after', identifier: 'identifier', method: 'specialMethod', event: MySpecialEvent::class)]
class MyEventListenerWithAttribute
{
    public function specialMethod(MySpecialEvent $event)
    {

    }
}

?>
