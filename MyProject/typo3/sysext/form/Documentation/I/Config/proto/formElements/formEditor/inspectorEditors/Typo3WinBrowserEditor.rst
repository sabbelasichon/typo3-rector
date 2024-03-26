.. include:: /Includes.rst.txt


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.typo3winbrowsereditor:

=======================
[Typo3WinBrowserEditor]
=======================


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.typo3winbrowsereditor-introduction:

Introduction
============

Shows a popup window to select a record (e.g. pages or tt_content records) as you know it from within the form engine.


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.typo3winbrowsereditor-properties:

Properties
==========

.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.templatename-typo3winbrowsereditor:

templateName
------------

:aspect:`Data type`
      string

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      Yes

:aspect:`Related options`
      - :ref:`prototypes.\<prototypeIdentifier>.formEditor.formEditorPartials <prototypes.\<prototypeidentifier>.formeditor.formeditorpartials>`

:aspect:`value`
      Inspector-Typo3WinBrowserEditor

:aspect:`Good to know`
      - :ref:`"Inspector"<concepts-formeditor-inspector>`

:aspect:`Description`
      .. include:: properties/TemplateName.rst


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.identifier-typo3winbrowsereditor:
.. include:: properties/Identifier.rst


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.label-typo3winbrowsereditor:
.. include:: properties/Label.rst


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.propertypath-typo3winbrowsereditor:
.. include:: properties/PropertyPath.rst


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.buttonlabel-typo3winbrowsereditor:

buttonLabel
-----------

:aspect:`Data type`
      string

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      Yes

.. :aspect:`Related options`
      @ToDo

:aspect:`Good to know`
      - :ref:`"Inspector"<concepts-formeditor-inspector>`
      - :ref:`"Translate form editor settings"<concepts-formeditor-translation-formeditor>`

:aspect:`Description`
      The label for the button which opens the popup window.


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.browsabletype-typo3winbrowsereditor:

browsableType
-------------

:aspect:`Data type`
      string

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      Yes

.. :aspect:`Related options`
      @ToDo

:aspect:`Good to know`
      - :ref:`"Inspector"<concepts-formeditor-inspector>`

:aspect:`Description`
      The allowed selectable record types e.g 'pages' or 'tt_content'.


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.iconidentifier-typo3winbrowsereditor:

iconIdentifier
--------------

:aspect:`Data type`
      string

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      Yes

.. :aspect:`Related options`
      @ToDo

:aspect:`Good to know`
      - :ref:`"Inspector"<concepts-formeditor-inspector>`

:aspect:`Description`
      The icon to use for the button which triggers the record browser.


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.propertyvalidators-typo3winbrowsereditor:

propertyValidators
------------------

:aspect:`Data type`
      array

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      No

:aspect:`Related options`
      - :ref:`"formElementPropertyValidatorsDefinition"<prototypes.\<prototypeidentifier>.formeditor.formelementpropertyvalidatorsdefinition>`

:aspect:`Good to know`
      - :ref:`"Inspector"<concepts-formeditor-inspector>`

:aspect:`Description`
      This ``inspector editors`` is able to validate it's value through JavaScript methods.
      This JavaScript validators can be registered through ``getFormEditorApp().addPropertyValidationValidator()``.
      The first method argument is the identifier for such a validator.
      Every array value within ``propertyValidators`` must be equal to such an identifier.

      For example:

     .. code-block:: yaml

         propertyValidators:
           10: 'Integer'
           20: 'FormElementIdentifierWithinCurlyBracesExclusive'


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.propertyvalidatorsmode-typo3winbrowsereditor:

propertyValidatorsMode
----------------------

:aspect:`Data type`
      string

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      No

.. :aspect:`Related options`
      @ToDo

:aspect:`Default value`
      AND

:aspect:`possible values`
      OR/ AND

:aspect:`Good to know`
      - :ref:`"Inspector"<concepts-formeditor-inspector>`

:aspect:`Description`
      If set to 'OR' then at least one validator must be valid to accept the ``inspector editor`` value. If set to 'AND' then all validators must be valid.


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.fieldexplanationtext-typo3winbrowsereditor:

fieldExplanationText
--------------------

:aspect:`Data type`
      string

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      No

.. :aspect:`Related options`
      @ToDo

:aspect:`Good to know`
      - :ref:`"Inspector"<concepts-formeditor-inspector>`
      - :ref:`"Translate form editor settings"<concepts-formeditor-translation-formeditor>`

:aspect:`Description`
      A text which is shown at the bottom of the ``inspector editor``.
