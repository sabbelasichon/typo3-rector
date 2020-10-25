<?php

declare(strict_types=1);

namespace TYPO3\CMS\Frontend\Controller;

use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

if (class_exists(TypoScriptFrontendController::class)) {
    return;
}

final class TypoScriptFrontendController
{
    /**
     * Doctype to use.
     *
     * Currently set via PageGenerator
     *
     * @var string
     */
    public $xhtmlDoctype = '';

    /**
     * Page content render object.
     *
     * @var ContentObjectRenderer
     */
    public $cObj = '';

    /**
     * @var string
     */
    public $loginUser = '';

    /**
     * @var string
     */
    public $gr_list = '';

    /**
     * @var string
     */
    public $beUserLogin = '';

    /**
     * @var string
     */
    public $showHiddenPage = '';

    /**
     * @var string
     */
    public $showHiddenRecords = '';

    /**
     * Passed to TypoScript template class and tells it to force template rendering
     * @var bool
     */
    public $forceTemplateParsing = false;

    /**
     * The TypoScript template object. Used to parse the TypoScript template
     *
     * @var TemplateService
     */
    public $tmpl;

    public function initTemplate(): void
    {
    }

    public function __construct()
    {
        //fake template object, otherwise tests cannot access this property
        $this->tmpl = new TemplateService();
    }

    public function applyHttpHeadersToResponse(): void
    {

    }

    public function processContentForOutput(): void
    {

    }

    public function processOutput(): void
    {

    }
}
