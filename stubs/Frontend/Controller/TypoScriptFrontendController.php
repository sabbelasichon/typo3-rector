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
     * Doctype to use
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

    public function initTemplate(): void
    {
    }
}
