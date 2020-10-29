<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\TypoScript;

if (class_exists(TemplateService::class)) {
    return;
}

class TemplateService
{
    /**
     * Passed to TypoScript template class and tells it to force template rendering
     * @var bool
     */
    public $forceTemplateParsing = false;
}
