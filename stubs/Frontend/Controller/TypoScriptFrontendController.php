<?php

declare(strict_types=1);

namespace TYPO3\CMS\Frontend\Controller;

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

    public function initTemplate(): void
    {
    }
}
