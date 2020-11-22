<?php
declare(strict_types=1);

namespace TYPO3\CMS\Taskcenter\Controller;

if(class_exists(TaskModuleController::class)) {
    return;
}

final class TaskModuleController
{
    /**
     * @var string
     */
    public $content = '';

    public function printContent(): void
    {
    }
}
