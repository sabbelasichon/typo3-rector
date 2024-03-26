.. include:: /Includes.rst.txt

.. _template-file-structure:

=============================
Fluid Template File Structure
=============================

Fluid uses exactly three types of template files:

* Templates which are *the individual files you either render directly or resolve using a controller name and action*
* Layouts which are *optional, shared files that are used by Templates and which render sections from Templates*
* Partials which are *the shared files that can be rendered from anywhere inside Fluid and contain reusable design bits*

When referring to these the names are used with an uppercase starting letter
(i.e. the name of the type). When referring to any file containing Fluid, the
word "templates" is sometimes used (i.e. lowercase starting letter) and in this
case refers to all the types above as a group.

Inside Templates and Partials a special `<f:section>` container can be used to
define sections that can be rendered using the `<f:render>` tag. Both Templates
and Partials may both contain and render sections, but Layouts may only
**render** sections.

The API
=======

Fluid uses a class type called `TemplatePaths` which gets passed to the
`TemplateView` and can resolve and deliver template file paths and sources.
In order to change the default paths you can set new ones in the `TemplatePaths`
object before you pass it to the `TemplateView`:

.. code-block:: php

    // set up paths object with arrays of paths with files
    $paths = new \TYPO3Fluid\Fluid\View\TemplatePaths();
    $paths->setTemplateRootPaths(['/path/to/templates/']);
    $paths->setLayoutRootPaths(['/path/to/layouts/']);
    $paths->setPartialRootPaths(['/path/to/partials/']);
    // pass the constructed TemplatePaths instance to the View
    $view = new \TYPO3Fluid\Fluid\View\TemplateView($paths);

Note that paths are *always defined as arrays*. In the default `TemplatePaths`
implementation, Fluid supports lookups in multiple template file locations -
which is very useful if you are rendering template files from another package
and wish to replace just a few template files. By adding your own template files
path *last in the paths arrays* Fluid will check those paths *first*.

Templates
=========

In Fluid, Templates can be referenced in two different ways:

* Directly by file path and filename
* Resolved using a controller name and action (and format)

Direct usage is of course done by simply setting the full path to the template
file that must be rendered; no magic in that.

In an MVC (model-view-controller) context the latter can be used to implement a
universal way to resolve the template files so you do not have to set the file
path and filename for each file you want to render. In this case, Fluid will
resolve template by using the pattern `{$templateRootPath}/{$controllerName}/{$actionName}.{$format}`
with all of these variables coming directly from the `TemplatePaths` instance -
which means that by filling the `TemplatePaths` instance with information about
your MVC context you can have Fluid automatically resolve the paths of template
files associated with controller actions.

Templates may or may not use a Layout. The Layout can be indicated by the use of
`<f:layout name="LayoutName" />` in the template source, or by the special variable `layoutName` if assigned to the template.

Fluid will behave slightly different when a Template uses a Layout and when it
does not:

* When no Layout is used, *the template is rendered directly* and will output
  everything not contained in an `<f:section>`
* When a Layout is used, *the Template itself is not rendered directly*.
  Instead, the Template defines any number of `<f:section>` which contain the
  pieces that will be rendered from the Layout using `<f:render>`

You can choose freely between using a Layout and not using one - even when
rendering templates in an MVC context, some Templates might use Layouts and
others might not. Whether or not you use Layouts of course depends on the
design you are trying to convey.

* `An example Template without a Layout <https://github.com/TYPO3/Fluid/blob/main/examples/Resources/Private/Singles/LayoutLess.html>`__
* `An example Template with a Layout <https://github.com/TYPO3/Fluid/blob/main/examples/Resources/Private/Templates/Default/Default.html>`__ and the
  `Layout used by that Template <https://github.com/TYPO3/Fluid/blob/main/examples/Resources/Private/Layouts/Default.html>`__

Layouts
=======

Layouts are as the name implies a layout for composing the individual bits of
the design. When your design uses a shared HTML design with just smaller pieces
being interchangeable (which most web applications do) your Layout can contain
the container HTML and the individual Templates can define the smaller design
bits that get used by the Layout.

The Template in this case defines a number of `<f:section>` containers which the
Layout renders with `<f:render>`. In application terms, the rendering engine
switches to the Layout when it detects one and renders it while preserving the
Template's context of controller name and action name.

* `An example Layout <https://github.com/TYPO3/Fluid/blob/main/examples/Resources/Private/Layouts/Default.html>`__ and
  `Template which uses it <https://github.com/TYPO3/Fluid/blob/main/examples/Resources/Private/Templates/Default/Default.html>`__

Partials
========

Partials are the smallest design bits that you can use when you need to have
reusable bits that can be rendered from multiple Templates, Layouts or even
other Partials. To name a few types of design bits that make sense as Partials:

* Address renderings
* Lists rendered from arrays
* Article metadata blocks
* Structured Data Markup

The trick with Partials is they can expect a generically named but predictably
structured object (such as an Address domain object instance, an array of string
values, etc). When rendering the Partial, the data can then be picked from any
source that fulfills the requirements. In the example of an Address, such an
object might be found on both a Person and a Company, in which case we can
render the same partial but with different sources:

* `<f:render partial="Address" arguments="{address: person.address}" />`
* `<f:render partial="Address" arguments="{address: company.address}" />`

The Partial then expects the variable `{address}` with all the properties
required to render an address; street, city, etc.

A Partial may or may not contain `<f:section>`. If it does contain `<f:section>`
containers then the contents of those containers can be rendered anywhere,
including inside the Partial itself, by `<f:render partial="NameOfPartial" section="NameOfSection" />`.
Partials without sections can be rendered by just
`<f:render partial="NameOfPartial" />` (with or without `arguments`).

* `An example of a Partial template without sections <https://github.com/TYPO3/Fluid/blob/main/examples/Resources/Private/Partials/FirstPartial.html>`__
* `An example of a Partial template with sections <https://github.com/TYPO3/Fluid/blob/main/examples/Resources/Private/Partials/Structures.html>`__
