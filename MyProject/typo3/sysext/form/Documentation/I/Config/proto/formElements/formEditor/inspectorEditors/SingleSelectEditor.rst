.. include:: /Includes.rst.txt


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.singleselecteditor:

====================
[SingleSelectEditor]
====================

.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.singleselecteditor-introduction:

Introduction
============

Shows a single select list with values. If a selectoption is selected, then the option value will be written within a form element property which is defined by the "propertyPath" option.


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.singleselecteditor-properties:

Properties
==========

.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.templatename-singleselecteditor:

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
      Inspector-SingleSelectEditor

:aspect:`Good to know`
      - :ref:`"Inspector"<concepts-formeditor-inspector>`

:aspect:`Description`
      .. include:: properties/TemplateName.rst


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.identifier-singleselecteditor:
.. include:: properties/Identifier.rst


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.label-singleselecteditor:
.. include:: properties/Label.rst


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.propertypath-singleselecteditor:
.. include:: properties/PropertyPath.rst


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.selectoptions.*.value-singleselecteditor:

selectOptions.[*].value
-----------------------

:aspect:`Data type`
      string

:aspect:`Needed by`
      Backend (form editor)


:aspect:`Mandatory`
      Yes

.. :aspect:`Related options`
      @ToDo

.. :aspect:`Good to know`
      ToDo

:aspect:`Description`
      The value which should be written into the corresponding form elements property.
      The corresponding form elements property is identified by the ``propertyPath`` option.


.. _prototypes.<prototypeIdentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.selectoptions.*.label-singleselecteditor:

selectOptions.[*].label
-----------------------

:aspect:`Data type`
      string

:aspect:`Needed by`
      Backend (form editor)


:aspect:`Mandatory`
      Yes

.. :aspect:`Related options`
      @ToDo

.. :aspect:`Good to know`
      ToDo

:aspect:`Description`
      The label which is shown within the select field.
