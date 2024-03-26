.. include:: /Includes.rst.txt

.. _what-are-viewhelpers:

=====================
What Are ViewHelpers?
=====================

ViewHelpers are special classes which build on base classes provided by Fluid.
These classes can then be imported and used as part of the Fluid language. Your
own, or some third-party package, may provide ViewHelper classes - some may even
require you to use their versions of ViewHelpers. Contrary to the built-in
ViewHelpers, such third-party ViewHelpers must be imported. In Fluid, a
collection of ViewHelpers is always identified by a short name that matches a
longer PHP namespace that is used as prefix for classes when resolving which PHP
class corresponds to a certain ViewHelper.

Registering/importing ViewHelpers
=================================

When you need to use third-party ViewHelpers in your templates there are two
equally valid options. The first of which makes your registered namespace
available in *all template files* without further importing:

.. code-block:: php

    $view = new TemplateView();
    $view->getRenderingContext()->getViewHelperResolver()->addNamespace('foo', 'Vendor\\Foo\\ViewHelpers');

And the latter method which can be used in each template file that requires the
ViewHelpers:

.. code-block:: xml

    <html xmlns:foo="Vendor\Foo\ViewHelpers">
    <f:layout name="Default" />
        <f:section name="Main">
            <!-- ... --->
        </f:section>
    </html>

Or using the alternative `xmlns` approach:

.. code-block:: xml

    <html xmlns:foo="http://typo3.org/ns/Vendor/Foo/ViewHelpers">
    <f:layout name="Default" />
        <f:section name="Main">
            <!-- ... --->
        </f:section>
    </html>

Once you have registered/imported the ViewHelper collection (we call it a
collection here even if it contains only one class) you can start using it in
your templates via the namespace alias you used when registering (in this
example: `foo` is the alias name).

Using ViewHelpers in templates
==============================

ViewHelpers work by accepting either one or both of tag content (which can be
HTML or other variables) and arguments which are defined as tag attributes. How
you write ViewHelper syntax is documented in the
:doc:`chapter about syntax </Usage/Syntax>` - with a few examples.

Which arguments a particular ViewHelper supports and which ViewHelpers are
available is determined by the packages you have installed. If you only have
Fluid installed, there are only the ViewHelpers in
`src/ViewHelpers <https://github.com/TYPO3/Fluid/tree/main/src/ViewHelpers>`__
which you can use. See also the documentation of any third-party packages you
use; such documentation should also describe ViewHelpers.

To know which arguments a ViewHelper supports and what does arguments do, the
most basic and always available way is to inspect the class that corresponds to
a ViewHelper. Such classes are usually placed in the `Vendor\Package\ViewHelpers`
PHP namespace (where `Vendor` and `Package` are obviously placeholders for actual
values) and follow the following naming convention:

* `v:format.raw` becomes PHP class `TYPO3Fluid\Fluid\ViewHelpers\Format\RawViewHelper`
* `v:render` becomes PHP class `TYPO3Fluid\Fluid\ViewHelpers\RenderViewHelper`
* `mypkg:custom.specialFormat` becomes PHP class
  `My\Package\ViewHelpers\Custom\SpecialFormatViewHelper` assuming you added
  `xmlns:mpkg="My\Package\ViewHelpers"` or alternative namespace registration
  (see above).

And so on.

The arguments a ViewHelper supports will be verbosely registered in the
`initializeArguments` function of each ViewHelper class. Inspect this method to
see the names, types, descriptions, required flag and default value of all
attributes. An example argument definition looks like this:

.. code-block:: php

    public function initializeArguments() {
        $this->registerArgument('myArgument', 'boolean', 'If TRUE, makes ViewHelper do foobar', FALSE, FALSE);
    }

Which translated to human terms means that we:

* Register an argument named `myArgument`
* Specify that it must be a boolean value or an expression resulting in a
  boolean value (you can find a few examples of such expressions in the
  `conditions example <https://github.com/TYPO3/Fluid/blob/main/examples/Resources/Private/Singles/Conditions.html>`__.
  Other valid types are `integer`, `string`, `float`, `array`, `DateTime` and
  other class names.
* Describe the argument's behavior in simple terms.
* Specify that the argument is not required (the 4th argument is `FALSE`).
* Specify that if the argument is not written when calling the ViewHelper,
  a default value of `FALSE` is assumed (5th argument).

The ViewHelper itself would then - assuming the class was named as our example
above - be callable using:

.. code-block:: xml

    <mypkg:custom.specialFormat myArgument="TRUE">{somevariable}</mypkg:custom.specialFormat>

What the argument does is then decided by the ViewHelper.

ViewHelper Schema
=================

Fluid supports autocompletion of the special Fluid tags via the use of an XSD
schema - a standard feature of the XML toolchain which allows defining required
attributes, expected attribute types and more. Some IDEs support the mapping of
such XSD schemas to namespace URLs which you can include in Fluid templates.
See `namespaces example file <https://github.com/TYPO3/Fluid/blob/main/examples/Resources/Private/Singles/Namespaces.html>`__
for details about how to define namespaces in Fluid templates - and see your
IDE's documentation for that part of the task).

When installed with development dependencies, `TYPO3.Fluid` includes a CLI
command that can generate XSD schema files for both the native ViewHelpers and
any inside your own packages. To use this command:

.. code-block:: bash

    ./vendor/bin/generateschema TYPO3Fluid\\Fluid\\ViewHelpers src/ViewHelpers > schema.xsd

Replace the first and second parameters with your own PHP namespace prefix and
path to your ViewHelper class files, respectively, to generate a schema file for
your own ViewHelpers.

If you installed `TYPO3.Fluid` as dependency or prevented installing development
dependencies you will need to manually install the schema generating utility:

.. code-block:: bash

    composer require typo3fluid/fluid-schema-generator

After which you can use the command like the examples illustrate.
