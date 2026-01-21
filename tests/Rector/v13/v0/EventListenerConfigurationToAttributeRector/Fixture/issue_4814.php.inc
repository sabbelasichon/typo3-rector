<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\Source\MySpecialEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;

#[AsEventListener(identifier: 'my-identifier', method: 'specialMethod')]
#[AsEventListener(identifier: 'my-other-identifier', method: 'otherSpecialMethod')]
class Issue4814
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

class Issue4814
{
    #[AsEventListener(identifier: 'my-identifier')]
    public function specialMethod(MySpecialEvent $event): void
    {
    }
    #[AsEventListener(identifier: 'my-other-identifier')]
    public function otherSpecialMethod(MySpecialEvent $event): void
    {
    }
}

?>
