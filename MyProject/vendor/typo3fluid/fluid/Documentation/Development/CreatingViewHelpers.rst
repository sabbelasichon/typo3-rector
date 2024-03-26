.. include:: /Includes.rst.txt

.. _creating-viewhelpers:

====================
Creating ViewHelpers
====================

Creating a ViewHelper is extremely simple. First, make sure you read the
:doc:`chapter about using ViewHelpers </Usage/ViewHelpers>` so you know where
ViewHelper class files are expected to be placed in your own package and that
you understand how/why you would require a custom ViewHelper.

Let's create an example ViewHelper which will accept exactly two arguments, both
arrays, and use those arrays to create a new array using `array_combine` which
takes one argument with keys and another of the same size with values. We would
like this new ViewHelper to be usable in inline syntax - for example as value of
the `each` argument on `f:for` to iterate the combined array. And finally, we
would like to be able to specify the "values" array also in the special inline
syntax for tag content:

.. code-block:: xml

    <html xmlns:mypkg="Vendor\Package\ViewHelpers">
    <dl>
        <f:for each="{myValuesArray -> mypkg:combine(keys: myKeysArray)}" as="item" key="key">
            <dt>{key}</dt>
            <dd>{item}</dd>
        </f:for>
    <dl>
   </html>

To enable this usage we must then create a ViewHelper class:

.. code-block:: php

    <?php
    namespace Vendor\Package\ViewHelpers;

    use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

    /**
     * This ViewHelper takes two arrays and returns
     * the `array_combine`d result.
     */
    class CombineViewHelper extends AbstractViewHelper {

        /**
         * @return void
         */
        public function initializeArguments() {
            $this->registerArgument('values', 'array', 'Values to use in array_combine');
            $this->registerArgument('keys', 'array', 'Keys to use in array_combine', TRUE);
        }

        /**
         * Combines two arrays using one for keys and
         * the other for values. If values are not provided
         * in argument it can be provided as tag content.
         *
         * @return array
         */
        public function render() {
            $values = $this->arguments['values'];
            $keys = $this->arguments['keys'];
            if ($values === NULL) {
                $values = $this->renderChildren();
            }
            return array_combine($keys, $values);
        }
    }

And that's all this class requires to work in the described way.

Note that in this example the ViewHelper's `render()` method returns an array
which means you can't use it in tag mode:

.. code-block:: xml

    <html xmlns:mypkg="Vendor\Package\ViewHelpers">
    <!-- BAD USAGE. Will output string value "Array" -->
    <mypkg:combine keys="{myKeysArray}">{myValuesArray}</mypkg:combine>
    </html>

Naturally this implies that any ViewHelper which returns a string compatible
value (including numbers and objects which have a `__toString()` method) can be
used in tag mode or inline mode - whereas ViewHelpers that return other data
types normally only make sense to use in inline mode; as values of other
ViewHelpers' attributes that require the returned value type. For example,
ViewHelpers which format output should always return a string (examples of such
ViewHelpers might be ones that implement `strip_tags`, `nl2br` or other
string-manipulating PHP functions). And data ViewHelpers may return any type,
but must be used a bit more carefully.

In other words: be careful what data types your ViewHelper returns.
Non-string-compatible values may cause problems if you use the ViewHelper in
ways that were not intended. Like in PHP, data types must either match or be
mutually compatible.
