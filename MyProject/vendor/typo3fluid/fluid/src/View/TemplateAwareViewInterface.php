<?php

declare(strict_types=1);

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\View;

/**
 * Optional addition to ViewInterface if the view deals with template files.
 *
 * @api
 */
interface TemplateAwareViewInterface
{
    /**
     * @param string $templateName A template name to render, e.g. "Main/Index"
     * @return string The rendered view
     * @api
     */
    public function render(string $templateName = '');
}
