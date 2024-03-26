<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\ViewHelpers\Cache;

use TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to disable template compiling
 *
 * Inserting this ViewHelper at any point in the template,
 * including inside conditions which do not get rendered,
 * will forcibly disable the caching/compiling of the full
 * template file to a PHP class.
 *
 * Use this if for whatever reason your platform is unable
 * to create or load PHP classes (for example on read-only
 * file systems or when using an incompatible default cache
 * backend).
 *
 * Passes through anything you place inside the ViewHelper,
 * so can safely be used as container tag, as self-closing
 * or with inline syntax - all with the same result.
 *
 * Examples
 * ========
 *
 * Self-closing
 * ------------
 *
 * ::
 *
 *     <f:cache.disable />
 *
 * Inline mode
 * -----------
 *
 * ::
 *
 *     {f:cache.disable()}
 *
 *
 * Container tag
 * -------------
 *
 * ::
 *
 *     <f:cache.disable>
 *        Some output or Fluid code
 *     </f:cache.disable>
 *
 * Additional output is also not compilable because of the ViewHelper
 *
 * @api
 */
class DisableViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @return string
     */
    public function render()
    {
        return $this->renderChildren();
    }

    /**
     * @param string $argumentsName
     * @param string $closureName
     * @param string $initializationPhpCode
     * @return string
     */
    public function compile($argumentsName, $closureName, &$initializationPhpCode, ViewHelperNode $node, TemplateCompiler $compiler)
    {
        $compiler->disable();
        return '';
    }
}
