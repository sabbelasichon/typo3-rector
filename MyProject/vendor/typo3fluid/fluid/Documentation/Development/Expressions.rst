.. include:: /Includes.rst.txt

.. _creating-expressionnodes:

========================
Creating ExpressionNodes
========================

To understand what an ExpressionNode is and which cases can be solved by
implementing custom ones, first read the
:doc:`chapter about implementing Fluid </Development/Implementation>`. Once you
grasp what an ExpressionNode is and how it works, a very brief example is all
you need.

First: an ExpressionNode is always one PHP class. Where you place it is
completely up to you - but to have the class actually be detected and used by
Fluid, *the class name must be returned from a custom ViewHelperResolver*.
This concept is also explained in the implementation chapter.

In Fluid's default ViewHelperResolver, the following code is responsible for
returning expression node class names:

.. code-block:: php

    /**
     * List of class names implementing ExpressionNodeInterface
     * which will be consulted when an expression does not match
     * any built-in parser expression types.
     *
     * @var string
     */
    protected $expressionNodeTypes = [
        'TYPO3Fluid\\Fluid\\Core\\Parser\\SyntaxTree\\Expression\\CastingExpressionNode',
        'TYPO3Fluid\\Fluid\\Core\\Parser\\SyntaxTree\\Expression\\MathExpressionNode',
        'TYPO3Fluid\\Fluid\\Core\\Parser\\SyntaxTree\\Expression\\TernaryExpressionNode',
    ];

    /**
     * @return string
     */
    public function getExpressionNodeTypes()
    {
        return $this->expressionNodeTypes;
    }

You may or may not want the listed expression nodes included, but if you change
the available expression types you should of course document this difference
about your implementation.

The following are fairly normal ways of replacing or extending this array of
class names:

* Override the property `$expressionNodeTypes` and define your own array
* Override the `getExpressionNodeTypes` method and return a complete array
* Override the `getExpressionNodeTypes` method and modify/append the array from
  `parent::getExpressionNodeTypes`

Once you are ready to create your ExpressionNode class, all you need is a very
simple one. The following class is the ternary ExpressionNode from Fluid itself
which detects the `{a ? b : c}` syntax and evaluates `a` as boolean and if true,
renders `b` else renders `c`. To get this behavior, we need a (relatively
simple) regular expression and one method to evaluate the expression while being
aware of the rendering context (which stores all variables, controller name,
action name etc).

.. code-block:: php

    <?php
    namespace TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression;

    use TYPO3Fluid\Fluid\Core\Parser;
    use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

    /**
     * Ternary Condition Node - allows the shorthand version
     * of a condition to be written as `{var ? thenvar : elsevar}`
     */
    class TernaryExpressionNode extends AbstractExpressionNode
    {
        /**
         * Pattern which detects ternary conditions written in shorthand
         * syntax, e.g. {checkvar ? thenvar : elsevar}.
         */
        public static $detectionExpression = '/
            (
                {                                # Start of shorthand syntax
                    (?:                          # Math expression is composed of...
                        [a-zA-Z0-9.]+            # Check variable side
                        [\s]+\?[\s]+
                        [a-zA-Z0-9.\s]+          # Then variable side
                        [\s]+:[\s]+
                        [a-zA-Z0-9.\s]+          # Else variable side
                    )
                }                                # End of shorthand syntax
            )/x';

        /**
         * @param RenderingContextInterface $renderingContext
         * @param string $expression
         * @return mixed
         */
        public static function evaluateExpression(RenderingContextInterface $renderingContext, $expression)
        {
            $parts = preg_split('/([\?:])/s', $expression); // split our expression on "?" and ":" characters
            $parts = array_map([__CLASS__, 'trimPart'], $parts); // parent::trimPart() is a utility method to trim
            list ($check, $then, $else) = $parts; // we expect *exactly* three parts, nothing more, nothing less
            // we evaluate the "check this" side of the expression as boolean...
            $checkResult = Parser\SyntaxTree\BooleanNode::convertToBoolean(parent::getTemplateVariableOrValueItself($check, $renderingContext));
            // ...then render the appropriate variable reference or string output depending on that decision.
            if ($checkResult) {
                return parent::getTemplateVariableOrValueItself($then, $renderingContext);
            } else {
                return parent::getTemplateVariableOrValueItself($else, $renderingContext);
            }
        }
    }

Taking from this example class the following are the rules you must observe:

1. Your ExpressionNode class name must be returned from your custom
   ViewHelperResolver.
2. You must either subclass the `AbstractExpressionNode` class or implement the
   `ExpressionNodeInterface` (subclassing the right class will automatically
   implement the right interface).
3. You must provide the class with an exact property called
   `public static $detectionExpression` which contains a string that is a perl
   regular expression which will result in at least one match when run against
   expressions you type in Fluid. It is **vital** that the property be both
   static and public and have the right name - it is accessed without
   instantiating the class.
4. The class must have a `public static function evaluateExpression` taking
   exactly the arguments above - nothing more, nothing less. The method must be
   able to work in a static context (it is called this way once templates have
   been compiled).
5. The `evaluateExpression` method may return any value type you desire, but
   like ViewHelpers, returning a non-string-compatible value implies that you
   should be careful about how you then use the expression; attempting to render
   a non-string-compatible value as a string may cause PHP warnings.
