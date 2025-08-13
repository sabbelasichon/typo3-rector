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

#[AsEventListener(identifier: 'identifier', event: MySpecialEvent::class, method: 'specialMethod', before: 'before', after: 'after')]
class MyEventListenerWithAttribute
{
    public function specialMethod(MySpecialEvent $event)
    {
    }
}

?>
