<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\Source\MySpecialEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;

#[AsEventListener('my-identifier', MySpecialEvent::class, 'specialMethod')]
#[AsEventListener('my-other-identifier', MySpecialEvent::class, 'otherSpecialMethod')]
class MyEventListenerWithPositionalArgument
{
    public function specialMethod(MySpecialEvent $event): void
    {
    }

    public function otherSpecialMethod(MySpecialEvent $event): void
    {
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\Source\MySpecialEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;

class MyEventListenerWithPositionalArgument
{
    #[AsEventListener(identifier: 'my-identifier', event: MySpecialEvent::class)]
    public function specialMethod(MySpecialEvent $event): void
    {
    }
    #[AsEventListener(identifier: 'my-other-identifier', event: MySpecialEvent::class)]
    public function otherSpecialMethod(MySpecialEvent $event): void
    {
    }
}

?>
