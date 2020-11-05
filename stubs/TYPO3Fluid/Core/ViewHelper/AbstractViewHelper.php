<?php
declare(strict_types=1);

namespace TYPO3Fluid\Fluid\Core\ViewHelper;

if (class_exists(AbstractViewHelper::class)) {
    return;
}

class AbstractViewHelper
{
    public function initializeArguments(): void
    {

    }
}
