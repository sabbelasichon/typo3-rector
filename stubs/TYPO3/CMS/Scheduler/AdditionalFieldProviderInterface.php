<?php
declare(strict_types=1);

namespace TYPO3\CMS\Scheduler;

use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;

if (interface_exists('TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface')) {
    return;
}

interface AdditionalFieldProviderInterface
{
    public function getAdditionalFields(array &$taskInfo, $task, SchedulerModuleController $schedulerModule);
}
