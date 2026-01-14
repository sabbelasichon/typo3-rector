<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\Fixture;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\Source\MySpecialEvent;

#[AsEventListener(identifier: 'my-identifier')]
class MyEventListenerWithExistingAnnotation
{
    public function __invoke(MySpecialEvent $event)
    {
    }
}
