.. include:: /Includes.rst.txt

.. _implementations:

==================
Implementing Fluid
==================

`TYPO3.Fluid` provides a standard implementation which works great on simple MVC
frameworks and as standalone rendering engine. However, the standard
implementation may lack certain features needed by the product into which you
are integrating `TYPO3.Fluid`.

To make sure you are able to override key behaviors of `TYPO3.Fluid` the package
will delegate much of the resolving, instantiation, argument mapping and
rendering of ViewHelpers to special classes which can be both manipulated and
overridden by the user. These special classes and their use cases are:

TemplateView
============

A fairly standard View implementation. The default object expects
`TemplatePaths` as constructor argument and has a handful of utility methods
like `$view->assign('variablename', 'value');`. Custom View types can be
implemented by subclassing the default class - but in order to avoid
problems, make sure you also call the original class' constructor method.

Creating a custom View allows you to change just a few aspects, mainly about
composition: which implementations of `TemplatePaths` the View requires, if it
needs a custom `ViewHelperResolver`, if it must have some default variables, if
it should have a default cache, etc.

.. note::

    The special variable `layoutName` is reserved and can be assigned to a
    template to set its Layout instead of using `<f:layout name="LayoutName" />`.

TemplatePaths
=============

In the default `TemplatePaths` object included with `TYPO3.Fluid` we provide a
set of conventions for resolving the template files that go into rendering a
Fluid template - the templates themselves, plus partials and layouts.

You should use the default `TemplatePaths` object if:

1. You are able to place your template files in folders that match the
   `TYPO3.Fluid` conventions, including the convention of subfolders named the
   same as your controllers.
2. You are able to provide the template paths that get used as an array with
   which `TemplatePaths` can be initialized.
3. Or you are able to individually set each group of paths.
4. You are able to rely on standard format handling (`format` simply being the
   file extension of template files).

And you should replace the `TemplatePaths` with your own subclass if:

1. You answered no to any of the above.
2. You want to be able to deliver template content before parsing, from other
   sources than files.
3. You want the resolving of template files for controller actions to happen in
   a different way.
4. You want to create other (caching-) identifiers for your partials, layouts
   and templates than defaults.

Whether you use your own class or the default, the `TemplatePaths` instance
*must be provided as first argument for the View*.

RenderingContext
================

Because `TYPO3.Fluid` was created in an MVC context it supports MVC behaviors,
including setting a "context" for your rendering process - to associate the
rendering with a controller and an action. The default `RenderingContext`
provided by `TYPO3.Fluid` has limited support: it supports a controller name
and an action name.

Should you require additional context variables - for example a package name,
a sub-controller identification, a user validation, the HTTP request object,
a Response object, or whatever - you can create your own type of
`RenderingContext` and pass that to the View. Doing this allows you to access
this `RenderingContext` from within ViewHelpers. One obvious purpose for this
is to *create custom links to controller actions*.

You should use the default `RenderingContext` object if:

1. You don't use an MVC context - you just render single templates possibly with
   partials and layouts.
2. You use MVC and are able to rely on just a controller name and action name
   for your implementation.
3. You can rely on the default way of storing template- and ViewHelper
   variables.

You should replace the `RenderingContext` with your own if:

1. You answered no to any of the above.
2. You require additional (framework/implementation-specific) attributes on the
   `RenderingContext`.
3. You require dynamic ways of returning the controller name, action name
   (or other custom attributes).
4. You wish to modify or replace the special `VariableContainer` objects that
   store variables to implement things like reserved variable names, persistent
   and auto-added variables and similar container-related operations.

Whether you use your own class or the default, the `RenderingContext` instance
*must be passed as second argument for the View*. If you do not pass a
`RenderingContext`, the default one will automatically be used.

FluidCache
==========

The caching of Fluid templates happens by compiling the templates to PHP files
which execute much faster than a parsed template ever could. These compiled
templates can only be stored if a `FluidCacheInterface`-implementing object is
provided. `TYPO3.Fluid` provides one such caching implementation: the
`SimpleFileCache` which just stores compiled PHP code in a designated directory.

Should you need to store the compiled templates in other ways you can implement
`FluidCacheInterface` in your caching object.

Whether you use your own cache class or the default, the `FluidCache`
*must be passed as third parameter for the View* or it
*must be assigned using `$view->setCache($cacheInstance)` before calling `$view->render()`*.

TemplateProcessor
=================

While custom `TemplatePaths` also allows sources of template files to be
modified before they are given to the TemplateParser, a custom `TemplatePaths`
implementation is sometimes overkill - and has the drawback of completely
overruling the reading of template file sources and making it up to the custom
class how exactly this processing happens.

In order to allow a more readily accessible and flexible way of pre-processing
template sources and affect key aspects of the parsing process, a
`TemplateProcessorInterface` is provided. Implementing this interface and the
methods it designates allows your class to be passed to the `TemplateView` and
be triggered every time a template source is parsed, right before parsing
starts:

.. code-block:: php

    $myTemplateProcessor = new MyTemplateProcessor();
    $myTemplateProcessor->setDoMyMagicThing(TRUE);
    $templateView->setTemplateProcessors([
        $myTemplateProcessor
    ]);

The registration method requires an array - this is to let you define multiple
processors without needing to wrap them in a single class as well as reuse
validation/manipulation across frameworks and only replace the parts that need
to be replaced.

This makes the method `preProcessSource($templateSource)` be called on this
class every time the TemplateParser is asked to parse a Fluid template.
Modifying the source and returning it makes that new template source be used.
Inside the TemplateProcessor method you have access to the TemplateParser and
ViewHelperResolver instances which the View uses.

The result is that TemplateProcessor instances are able to, for example:

* Validate template sources and implement reporting/logging of errors in for
  example a framework.
* Fix things like character encoding issues in template sources.
* Process Fluid code from potentially untrusted sources, for example doing XSS
  removals before parsing.
* Extract legacy namespace definitions and assign those to the
  ViewHelperResolver for active use.
* Extract legacy escaping instruction headers and assign those to the
  TemplateParser's Configuration instance.
* Enable the use of custom template code in file's header, extracted and used
  by a framework.

Note again: these same behaviors are possible using a custom `TemplatePaths`
implementation - but even with such a custom implementation this
TemplateProcessor pattern can still be used to manipulate/validate the sources
coming from `TemplatePaths`, providing a nice way to decouple paths resolving
from template source processing.

ViewHelperInvoker
=================

The `ViewHelperInvoker` is a class dedicated to validating current arguments of
and if valid, calling the ViewHelper's render method. The default object
supports only the arguments added via `initializeArguments` and
`(register|override)Argument` on the ViewHelper - and it does not use internal
instance caching; it creates and renders new ViewHelpers for every node.

You should replace the `ViewHelperInvoker` if:

1. You must support different ways of calling ViewHelpers such as alternative
   `setArguments` names.
2. You wish to change the way the invoker uses and stores ViewHelper instances,
   for example to use an internal cache.
3. You wish to change the way ViewHelper arguments are validated, for example
   changing the Exceptions that are thrown.
4. You wish to perform processing on the output of ViewHelpers, for example to
   remove XSS attempts according to your own rules.

.. note::

    ViewHelper instance creation and argument retrieval is handled by the
    ViewHelperResolver.

If you wish to use a custom `ViewHelperInvoker` you **must** do so via a custom
`ViewHelperResolver`. You are given the class name of the ViewHelper to resolve
a `ViewHelperInvoker` - which means you can also use different invokers for
different classes.

.. _implementation-view-helper-resolver:

ViewHelperResolver
==================

In `TYPO3.Fluid` most of your options for extending the language - for example,
adding new ways to format strings, to make special condition types, custom links
and such - are connected to ViewHelpers. These are the special classes that are
called using for exampel
`<f:format.htmlentities>{somestring}</f:format.htmlentities>`.

A ViewHelper is essentially referenced by the namespace and the path to the
ViewHelper, in this case `f` being the namespace and `format.htmlentities` being
the path.

The `ViewHelperResolver` is the class responsible for turning these two pieces
of information into an expected class name and when this class is resolved, to
retrieve from it the arguments you can use for each ViewHelper.

You should use the default `ViewHelperResolver` if:

1. You can rely on the default way of turning a namespace and path of a
   ViewHelper into a class name.
2. You can rely on the default way ViewHelpers return the arguments they
   support.
3. You can rely on instantiation of ViewHelpers happening through a simple
   `new $class()`.
4. You can rely on the default `ViewHelperInvoker`.

You should replace the `ViewHelperResolver` if:

1. You answered no to any of the above.
2. You want to make ViewHelper namespaces available in templates without
   importing.
3. You want to change which class is resolved from a given namespace and
   ViewHelper path, for example allowing you to add your own ViewHelpers to the
   default namespace or replace default ViewHelpers with your own.
4. You want to change the argument retrieval from ViewHelpers or you want to
   manipulate the arguments (for example, giving them a default value, making
   them optional, changing their data type).
5. You have to use a custom `ViewHelperInvoker` to actually render your
   ViewHelpers.

The default `ViewHelperResolver` can be replaced in one way only: calling
`$view->setViewHelperResolver($resolverInstance);` on the TemplateView. However,
a custom View class can of course replace this and other aspects of the View
such as `TemplatePaths`.

ExpressionNodes
===============

The `ExpressionNode` concept is the most profound way you can manipulate the
Fluid language itself, adding to it new syntax options that can be used inside
the shorthand `{...}` syntax. Normally you are confined to using ViewHelpers
when you want such special processing in your templates - but using
`ExpressionNodes` allows you to add these processings as actual parts of the
templating language itself; avoiding the need to include a ViewHelper namespace.

`TYPO3.Fluid` itself provides the following types of `ExpressionNodes`:

1. `MathExpressionNode` which scans for and evaluates simple mathematical
   expressions like `{variable + 1}`.
2. `TernaryExpressionNode` which implements a ternary condition in Fluid syntax
   like `{ifsomething ? thenoutputthis : elsethis}`
3. `CastingExpressionNode` which casts variables to a certain type, e.g.
   `{suspectType as integer}`, `{myInteger as boolean}`.

An `ExpressionNode` basically consists of one an expression matching pattern
(regex), one non-static method to evaluate the expression `public function
evaluate(RenderingContextInterface $renderingContext)` and a mirror of this
function which can be called statically:
`public static evaluteExpression(RenderingContextInterface $renderingContext, $expression)`.
The non-static method should then simply delegate to the static method and use
the expression stored in `$this->expression` as second parameter for the static
method call.

`ExpressionNodes` automatically support compilation and will generate compiled
code which stores the expression and calls the static `evaluateExpression`
method with the rendering context and the stored expression.

You should create your own `ExpressionNodes` if:

1. You want a custom syntax in your Fluid templates (theoretical example:
   casting variables using `{(integer)variablename}`).
2. You want to replace either of the above mentioned `ExpressionNodes` with ones
   using the same, or an expanded version of their regular expression patterns
   to further extend the strings they capture and process.

**Limitations**

1. Contrary to other nodes in Fluid, `ExpressionNodes` cannot be used in tag
   form. Only the shorthand/inline syntax is supported.
2. `ExpressionNodes` are not recursive unless they have recursive behavior
   internally (this is for example different from array nodes which match
   sub-arrays recursively). In other words: `ExpressionNodes` are intended for
   *simple syntaxes and variables*.

To create a new type of `ExpressionNode` - perhaps one that fits your framework:

1. Make sure you subclass
   `TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\AbstractExpressionNode`
   and implement `TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\ExpressionNodeInterface`
   in your class.
2. Make sure your class pass `public static $detectionExpression = '%s';` where
   `%s` is a perl regular expression which returns at least one match if the
   expression can be handled by your `ExpressionNode` class.
3. Make sure your class implements a
   `public static evaluateExpression(RenderingContextInterface $renderingContext, $expression)`
   method which will be able to process the expression in a statically called
   context.

Any `ExpressionNode` types added this way are also compilable off-the-bat. The
one thing you can't change is how this compiling happens - so if your
`ExpressionNode` does some heavy processing you may consider implementing a
dedicated cache for it.

Additional `ExpressionNode` class names can be returned from a custom
`ViewHelperResolver` (see above) by overriding the `getExpressionNodeTypes()`
method **and/or** the `protected $expressionTypes` property to append your class
names to the list. Each `ExpressionNode` is consulted in the order they appear
in this list - and only the first one that matches will be used.
