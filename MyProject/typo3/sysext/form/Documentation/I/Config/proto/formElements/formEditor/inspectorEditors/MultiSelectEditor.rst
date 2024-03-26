.. include:: /Includes.rst.txt


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.multiselecteditor:

===================
[MultiSelectEditor]
===================

.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.multiselecteditor-introduction:

Introduction
============

Shows a multiselect list with values. If one or more selectoptions are selected, then the option value will be written within a form element property which is defined by the "propertyPath" option.


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.multiselecteditor-properties:

Properties
==========

.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.templatename-multiselecteditor:

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
      Inspector-MultiSelectEditor

:aspect:`Good to know`
      - :ref:`"Inspector"<concepts-formeditor-inspector>`

:aspect:`Description`
      .. include:: properties/TemplateName.rst


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.identifier-multiselecteditor:
.. include:: properties/Identifier.rst


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.label-multiselecteditor:
.. include:: properties/Label.rst


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.propertypath-multiselecteditor:
.. include:: properties/PropertyPath.rst


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.selectoptions.*.value-multiselecteditor:

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


.. _prototypes.<prototypeidentifier>.formelementsdefinition.<formelementtypeidentifier>.formeditor.editors.*.selectoptions.*.label-multiselecteditor:

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
