<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Mvc\Controller;

if (class_exists(ActionController::class)) {
    return;
}

class ActionController extends AbstractController
{
    public function forward(string $actionName, string $controllerName = null, string $extensionName = null, array $arguments = null): void
    {
    }
}
