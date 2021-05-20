<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Controller;

if(class_exists('TYPO3\CMS\Core\Controller\ErrorPageController')) {
    return;
}

class ErrorPageController
{
    public function errorAction(
        string $title,
        string $message,
        int $severity = 0,
        int $errorCode = 0
    ): string {

    }
}
