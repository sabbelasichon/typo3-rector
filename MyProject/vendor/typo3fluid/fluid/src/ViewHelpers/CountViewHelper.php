<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

/**
 * This ViewHelper counts elements of the specified array or countable object.
 *
 * Examples
 * ========
 *
 * Count array elements
 * --------------------
 *
 * ::
 *
 *     <f:count subject="{0:1, 1:2, 2:3, 3:4}" />
 *
 * Output::
 *
 *     4
 *
 * inline notation
 * ---------------
 *
 * ::
 *
 *     {objects -> f:count()}
 *
 * Output::
 *
 *     10 (depending on the number of items in ``{objects}``)
 *
 * @api
 */
class CountViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    /**
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('subject', 'array', 'Countable subject, array or \Countable');
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return int
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $countable = $renderChildrenClosure();
        if ($countable === null) {
            return 0;
        }
        if (!$countable instanceof \Countable && !is_array($countable)) {
            throw new ViewHelper\Exception(
                sprintf(
                    'Subject given to f:count() is not countable (type: %s)',
                    is_object($countable) ? get_class($countable) : gettype($countable)
                )
            );
        }
        return count($countable);
    }
}
