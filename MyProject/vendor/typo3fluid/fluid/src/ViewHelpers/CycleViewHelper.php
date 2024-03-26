<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * This ViewHelper cycles through the specified values.
 * This can be often used to specify CSS classes for example.
 *
 * To achieve the "zebra class" effect in a loop you can also use the
 * "iteration" argument of the **for** ViewHelper.
 *
 * Examples
 * ========
 *
 * These examples could also be achieved using the "iteration" argument
 * of the ForViewHelper.
 *
 * Simple
 * ------
 *
 * ::
 *
 *     <f:for each="{0:1, 1:2, 2:3, 3:4}" as="foo">
 *         <f:cycle values="{0: 'foo', 1: 'bar', 2: 'baz'}" as="cycle">
 *             {cycle}
 *         </f:cycle>
 *     </f:for>
 *
 * Output::
 *
 *     foobarbazfoo
 *
 * Alternating CSS class
 * ---------------------
 *
 * ::
 *
 *     <ul>
 *         <f:for each="{0:1, 1:2, 2:3, 3:4}" as="foo">
 *             <f:cycle values="{0: 'odd', 1: 'even'}" as="zebraClass">
 *                 <li class="{zebraClass}">{foo}</li>
 *             </f:cycle>
 *         </f:for>
 *     </ul>
 *
 * Output::
 *
 *     <ul>
 *         <li class="odd">1</li>
 *         <li class="even">2</li>
 *         <li class="odd">3</li>
 *         <li class="even">4</li>
 *     </ul>
 *
 * @api
 */
class CycleViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('values', 'array', 'The array or object implementing \ArrayAccess (for example \SplObjectStorage) to iterated over');
        $this->registerArgument('as', 'strong', 'The name of the iteration variable', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $values = $arguments['values'];
        $as = $arguments['as'];
        if ($values === null) {
            return $renderChildrenClosure();
        }
        $values = static::initializeValues($values);
        $index = static::initializeIndex($as, $renderingContext->getViewHelperVariableContainer());

        $currentValue = isset($values[$index]) ? $values[$index] : null;

        $renderingContext->getVariableProvider()->add($as, $currentValue);
        $output = $renderChildrenClosure();
        $renderingContext->getVariableProvider()->remove($as);

        $index++;
        if (!isset($values[$index])) {
            $index = 0;
        }
        $renderingContext->getViewHelperVariableContainer()->addOrUpdate(static::class, $as, $index);

        return $output;
    }

    /**
     * @param mixed $values
     * @return array
     * @throws ViewHelper\Exception
     */
    protected static function initializeValues($values)
    {
        if (is_array($values)) {
            return array_values($values);
        }

        if ($values instanceof \Traversable) {
            return iterator_to_array($values, false);
        }

        throw new ViewHelper\Exception('CycleViewHelper only supports arrays and objects implementing \Traversable interface', 1248728393);
    }

    /**
     * @param string $as
     * @param ViewHelper\ViewHelperVariableContainer $viewHelperVariableContainer
     * @return int
     */
    protected static function initializeIndex($as, ViewHelper\ViewHelperVariableContainer $viewHelperVariableContainer)
    {
        $index = 0;
        if ($viewHelperVariableContainer->exists(static::class, $as)) {
            $index = $viewHelperVariableContainer->get(static::class, $as);
        }

        return $index;
    }
}
