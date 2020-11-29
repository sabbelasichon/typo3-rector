<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v3\UsePSR14EventsExtbaseRelatedSignalsFixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class UsePSR14EventsExtbaseRelatedSignalsFixture
{
    public function method(): void
    {
        $actionController = GeneralUtility::makeInstance(ActionController::class);
        $actionController->emitBeforeCallActionMethodSignal([]);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v3\UsePSR14EventsExtbaseRelatedSignalsFixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Controller\BeforeActionCallEvent;

class UsePSR14EventsExtbaseRelatedSignalsFixture
{
    public function method(): void
    {
        $actionController = GeneralUtility::makeInstance(ActionController::class);
        $actionController->eventDispatcher->dispatch(new BeforeActionCallEvent(static::class, 'indexAction', []));
    }
}

?>
