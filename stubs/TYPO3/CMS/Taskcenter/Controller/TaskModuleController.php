<?php
declare(strict_types=1);

namespace TYPO3\CMS\Taskcenter\Controller;

if(class_exists('TYPO3\CMS\Taskcenter\Controller\TaskModuleController')) {
    return;
}

class TaskModuleController
{
    /**
     * @var string
     */
    public $content = '';

    public function printContent(): void
    {
    }
}
