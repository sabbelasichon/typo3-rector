<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Applies PHP ``htmlspecialchars()`` escaping to a value.
 *
 * See http://www.php.net/manual/function.htmlspecialchars.php
 *
 * Examples
 * ========
 *
 * Default notation
 * ----------------
 *
 * ::
 *
 *     <f:format.htmlspecialchars>{text}</f:format.htmlspecialchars>
 *
 * Output::
 *
 *     Text with & " ' < > * replaced by HTML entities (htmlspecialchars applied).
 *
 * Inline notation
 * ---------------
 *
 * ::
 *
 *     {text -> f:format.htmlspecialchars(encoding: 'ISO-8859-1')}
 *
 * Output::
 *
 *     Text with & " ' < > * replaced by HTML entities (htmlspecialchars applied).
 *
 * @api
 */
class HtmlspecialcharsViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * Disable the output escaping interceptor so that the value is not htmlspecialchar'd twice
     *
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('value', 'string', 'Value to format');
        $this->registerArgument('keepQuotes', 'boolean', 'If TRUE quotes will not be replaced (ENT_NOQUOTES)', false, false);
        $this->registerArgument('encoding', 'string', 'Encoding', false, 'UTF-8');
        $this->registerArgument('doubleEncode', 'boolean', 'If FALSE html entities will not be encoded', false, true);
    }

    /**
     * Escapes special characters with their escaped counterparts as needed using PHPs htmlspecialchars() function.
     *
     * @return string the altered string
     * @see http://www.php.net/manual/function.htmlspecialchars.php
     * @api
     */
    public function render()
    {
        $value = $this->arguments['value'];
        $keepQuotes = $this->arguments['keepQuotes'];
        $encoding = $this->arguments['encoding'];
        $doubleEncode = $this->arguments['doubleEncode'];
        if ($value === null) {
            $value = $this->renderChildren();
        }

        if (!is_string($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            return $value;
        }
        $flags = $keepQuotes ? ENT_NOQUOTES : ENT_QUOTES;

        return htmlspecialchars($value, $flags, $encoding, $doubleEncode);
    }

    /**
     * This ViewHelper is used a *lot* because it is used by the escape interceptor.
     * Therefore we render it to raw PHP code during compilation
     *
     * @param string $argumentsName
     * @param string $closureName
     * @param string $initializationPhpCode
     * @param ViewHelperNode $node
     * @param TemplateCompiler $compiler
     * @return string
     */
    public function compile($argumentsName, $closureName, &$initializationPhpCode, ViewHelperNode $node, TemplateCompiler $compiler)
    {
        $valueVariableName = $compiler->variableName('value');
        $initializationPhpCode .= sprintf('%1$s = (%2$s[\'value\'] !== NULL ? %2$s[\'value\'] : %3$s());', $valueVariableName, $argumentsName, $closureName) . chr(10);

        return sprintf(
            '!is_string(%1$s) && !(is_object(%1$s) && method_exists(%1$s, \'__toString\')) ? %1$s : htmlspecialchars(%1$s, (%2$s[\'keepQuotes\'] ? ENT_NOQUOTES : ENT_QUOTES), %2$s[\'encoding\'], %2$s[\'doubleEncode\'])',
            $valueVariableName,
            $argumentsName
        );
    }
}
