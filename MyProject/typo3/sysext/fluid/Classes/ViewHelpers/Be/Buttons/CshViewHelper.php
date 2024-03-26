<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Fluid\ViewHelpers\Be\Buttons;

use TYPO3\CMS\Fluid\ViewHelpers\Be\AbstractBackendViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * ViewHelper which returns CSH (context sensitive help) button with icon.
 *
 * .. note::
 *    The CSH button will only work, if the current BE user has the "Context
 *    Sensitive Help mode" set to something else than "Display no help
 *    information" in the Users settings.
 *
 * .. note::
 *    This ViewHelper is experimental!
 *
 * Examples
 * ========
 *
 * Default::
 *
 *    <f:be.buttons.csh />
 *
 * CSH button as known from the TYPO3 backend.
 *
 * Full configuration::
 *
 *    <f:be.buttons.csh table="xMOD_csh_corebe" field="someCshKey" />
 *
 * CSH button as known from the TYPO3 backend with some custom settings.
 *
 * Full configuration with content::
 *
 *    <f:be.buttons.csh table="xMOD_csh_corebe" field="someCshKey">
 *       some text to link
 *    </f:be.buttons.csh>
 *
 * A link with text "some text to link" to link the help.
 *
 * @deprecated
 */
final class CshViewHelper extends AbstractBackendViewHelper
{
    /**
     * As this ViewHelper renders HTML, the output must not be escaped.
     *
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('table', 'string', 'Table name (\'_MOD_\'+module name). If not set, the current module name will be used');
        $this->registerArgument('field', 'string', 'Field name (CSH locallang main key)', false, '');
        $this->registerArgument('wrap', 'string', 'Markup to wrap around the CSH, split by "|"', false, '');
    }

    public function render(): string
    {
        return self::renderStatic($this->arguments, $this->buildRenderChildrenClosure(), $this->renderingContext);
    }

    /**
     * @throws \RuntimeException
     *
     * @deprecated The functionality has been removed in v12. The class will be removed in TYPO3 v13.
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext): string
    {
        trigger_error(
            __CLASS__ . ' only returns the $renderChildrenClosure() in TYPO3 v12 as compatibility layer and will be completely removed in TYPO3 v13.',
            E_USER_DEPRECATED
        );
        return (string)$renderChildrenClosure();
    }
}
