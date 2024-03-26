<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\Variables\StandardVariableProvider;
use TYPO3Fluid\Fluid\Core\ViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Grouped loop ViewHelper.
 * Loops through the specified values.
 *
 * The groupBy argument also supports property paths.
 *
 * Using this ViewHelper can be a sign of weak architecture. If you end up
 * using it extensively you might want to fine-tune your "view model" (the
 * data you assign to the view).
 *
 * Examples
 * ========
 *
 * Simple
 * ------
 *
 * ::
 *
 *     <f:groupedFor each="{0: {name: 'apple', color: 'green'}, 1: {name: 'cherry', color: 'red'}, 2: {name: 'banana', color: 'yellow'}, 3: {name: 'strawberry', color: 'red'}}"
 *         as="fruitsOfThisColor" groupBy="color"
 *     >
 *         <f:for each="{fruitsOfThisColor}" as="fruit">
 *             {fruit.name}
 *         </f:for>
 *     </f:groupedFor>
 *
 * Output::
 *
 *     apple cherry strawberry banana
 *
 * Two dimensional list
 * --------------------
 *
 * ::
 *
 *     <ul>
 *         <f:groupedFor each="{0: {name: 'apple', color: 'green'}, 1: {name: 'cherry', color: 'red'}, 2: {name: 'banana', color: 'yellow'}, 3: {name: 'strawberry', color: 'red'}}" as="fruitsOfThisColor" groupBy="color" groupKey="color">
 *             <li>
 *                 {color} fruits:
 *                 <ul>
 *                     <f:for each="{fruitsOfThisColor}" as="fruit" key="label">
 *                         <li>{label}: {fruit.name}</li>
 *                     </f:for>
 *                 </ul>
 *             </li>
 *         </f:groupedFor>
 *     </ul>
 *
 * Output::
 *
 *     <ul>
 *         <li>green fruits
 *             <ul>
 *                 <li>0: apple</li>
 *             </ul>
 *         </li>
 *         <li>red fruits
 *             <ul>
 *                 <li>1: cherry</li>
 *             </ul>
 *             <ul>
 *                 <li>3: strawberry</li>
 *             </ul>
 *         </li>
 *         <li>yellow fruits
 *             <ul>
 *                 <li>2: banana</li>
 *             </ul>
 *         </li>
 *     </ul>
 *
 * @api
 */
class GroupedForViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('each', 'array', 'The array or \SplObjectStorage to iterated over', true);
        $this->registerArgument('as', 'string', 'The name of the iteration variable', true);
        $this->registerArgument('groupBy', 'string', 'Group by this property', true);
        $this->registerArgument('groupKey', 'string', 'The name of the variable to store the current group', false, 'groupKey');
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $each = $arguments['each'];
        $as = $arguments['as'];
        $groupBy = $arguments['groupBy'];
        $groupKey = $arguments['groupKey'];
        $output = '';
        if ($each === null) {
            return '';
        }
        if (is_object($each)) {
            if (!$each instanceof \Traversable) {
                throw new ViewHelper\Exception('GroupedForViewHelper only supports arrays and objects implementing \Traversable interface', 1253108907);
            }
            $each = iterator_to_array($each);
        }

        $groups = static::groupElements($each, $groupBy);

        $templateVariableContainer = $renderingContext->getVariableProvider();
        foreach ($groups['values'] as $currentGroupIndex => $group) {
            $templateVariableContainer->add($groupKey, $groups['keys'][$currentGroupIndex]);
            $templateVariableContainer->add($as, $group);
            $output .= $renderChildrenClosure();
            $templateVariableContainer->remove($groupKey);
            $templateVariableContainer->remove($as);
        }
        return $output;
    }

    /**
     * Groups the given array by the specified groupBy property.
     *
     * @param array $elements The array / traversable object to be grouped
     * @param string $groupBy Group by this property
     * @return array The grouped array in the form array('keys' => array('key1' => [key1value], 'key2' => [key2value], ...), 'values' => array('key1' => array([key1value] => [element1]), ...), ...)
     * @throws ViewHelper\Exception
     */
    protected static function groupElements(array $elements, $groupBy)
    {
        $groups = ['keys' => [], 'values' => []];
        foreach ($elements as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $extractor = new StandardVariableProvider();
                $extractor->setSource($value);
                $currentGroupIndex = $extractor->getByPath($groupBy);
            } else {
                throw new ViewHelper\Exception('GroupedForViewHelper only supports multi-dimensional arrays and objects', 1253120365);
            }
            $currentGroupKeyValue = $currentGroupIndex;
            if ($currentGroupIndex instanceof \DateTime) {
                $currentGroupIndex = $currentGroupIndex->format(\DateTime::RFC850);
            } elseif (is_object($currentGroupIndex)) {
                $currentGroupIndex = spl_object_hash($currentGroupIndex);
            }
            $groups['keys'][$currentGroupIndex] = $currentGroupKeyValue;
            $groups['values'][$currentGroupIndex][$key] = $value;
        }
        return $groups;
    }
}
