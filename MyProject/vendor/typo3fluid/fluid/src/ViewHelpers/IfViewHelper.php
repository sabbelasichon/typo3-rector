<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 * This ViewHelper implements an if/else condition.
 *
 * Conditions:
 *
 * As a condition is a boolean value, you can just use a boolean argument.
 * Alternatively, you can write a boolean expression there.
 * Boolean expressions have the following form:
 *
 * XX Comparator YY
 *
 * Comparator is one of: ==, !=, <, <=, >, >= and %
 * The % operator converts the result of the % operation to boolean.
 *
 * XX and YY can be one of:
 *
 * - number
 * - Object Accessor
 * - Array
 * - a ViewHelper
 * - string
 *
 * ::
 *
 *       <f:if condition="{rank} > 100">
 *           Will be shown if rank is > 100
 *       </f:if>
 *       <f:if condition="{rank} % 2">
 *           Will be shown if rank % 2 != 0.
 *       </f:if>
 *       <f:if condition="{rank} == {k:bar()}">
 *           Checks if rank is equal to the result of the ViewHelper "k:bar"
 *       </f:if>
 *       <f:if condition="{foo.bar} == 'stringToCompare'">
 *           Will result in true if {foo.bar}'s represented value equals 'stringToCompare'.
 *       </f:if>
 *
 * Examples
 * ========
 *
 * Basic usage
 * -----------
 *
 * ::
 *
 *     <f:if condition="somecondition">
 *         This is being shown in case the condition matches
 *     </f:if>
 *
 * Output::
 *
 *     Everything inside the <f:if> tag is being displayed if the condition evaluates to TRUE.
 *
 * If / then / else
 * ----------------
 *
 * ::
 *
 *     <f:if condition="somecondition">
 *         <f:then>
 *             This is being shown in case the condition matches.
 *         </f:then>
 *         <f:else>
 *             This is being displayed in case the condition evaluates to FALSE.
 *         </f:else>
 *     </f:if>
 *
 * Output::
 *
 *     Everything inside the "then" tag is displayed if the condition evaluates to TRUE.
 *     Otherwise, everything inside the "else"-tag is displayed.
 *
 * inline notation
 * ---------------
 *
 * ::
 *
 *     {f:if(condition: someCondition, then: 'condition is met', else: 'condition is not met')}
 *
 * Output::
 *
 *     The value of the "then" attribute is displayed if the condition evaluates to TRUE.
 *     Otherwise, everything the value of the "else"-attribute is displayed.
 *
 * @api
 * @todo: Declare final with next major
 */
class IfViewHelper extends AbstractConditionViewHelper
{
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('condition', 'boolean', 'Condition expression conforming to Fluid boolean rules', false, false);
    }

    /**
     * @param array $arguments
     * @param RenderingContextInterface $renderingContext
     * @return bool
     */
    public static function verdict(array $arguments, RenderingContextInterface $renderingContext)
    {
        return (bool)$arguments['condition'];
    }
}
