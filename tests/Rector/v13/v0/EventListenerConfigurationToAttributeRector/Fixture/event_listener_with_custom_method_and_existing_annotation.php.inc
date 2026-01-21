<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\Fixture;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\Source\MySpecialEvent;

class MyEventListenerWithCustomMethodAndExistingAnnotation
{
    #[AsEventListener(identifier: 'my-identifier')]
    public function specialMethod(MySpecialEvent $event): void
    {
    }
}
