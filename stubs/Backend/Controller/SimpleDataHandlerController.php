<?php

declare(strict_types=1);

namespace TYPO3\CMS\Backend\Controller;

if (class_exists(SimpleDataHandlerController::class)) {
    return;
}

class SimpleDataHandlerController
{
    public $prErr;
    public $uPT;
}
