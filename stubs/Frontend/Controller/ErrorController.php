<?php

declare(strict_types=1);

namespace TYPO3\CMS\Frontend\Controller;

if (class_exists(ErrorController::class)) {
    return;
}

final class ErrorController
{
    public function unavailableAction($request, string $message, array $reasons = [])
    {
    }

    public function pageNotFoundAction($request, string $message, array $reasons = [])
    {
    }

    public function accessDeniedAction($request, string $message, array $reasons = [])
    {
    }
}
