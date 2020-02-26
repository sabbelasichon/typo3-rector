<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Service;

use TYPO3\CMS\Core\Page\PageRenderer;

if (class_exists(PageRenderer::class)) {
    return;
}

final class MarkerBasedTemplateService
{
}
