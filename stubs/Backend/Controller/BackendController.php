<?php
declare(strict_types=1);


namespace TYPO3\CMS\Backend\Controller;

use TYPO3\CMS\Core\Page\PageRenderer;

if (class_exists(BackendController::class)) {
    return;
}

final class BackendController
{
    public function getPageRenderer(): PageRenderer
    {
        return new PageRenderer();
    }
}
