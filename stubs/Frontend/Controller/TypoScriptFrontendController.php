<?php

declare(strict_types=1);

namespace TYPO3\CMS\Frontend\Controller;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

final class TypoScriptFrontendController
{
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
