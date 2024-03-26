.. include:: /Includes.rst.txt
formEditor.group
----------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.formElementsDefinition.Text.formEditor.group

:aspect:`Data type`
      string

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      Recommended

:aspect:`Default value (for prototype 'standard')`
      .. code-block:: yaml
         :linenos:
         :emphasize-lines: 3-

         Text:
           formEditor:
             group: input

:aspect:`Default value`
      Depends (see :ref:`concrete element configuration <prototypes.\<prototypeidentifier>.formelementsdefinition.\<formelementtypeidentifier>-concreteconfigurations>`)

:aspect:`Description`
      Define within which group within the ``form editor`` "new Element" modal the form element should be shown.
      The ``group`` value must be equal to an array key within ``formElementGroups``.
