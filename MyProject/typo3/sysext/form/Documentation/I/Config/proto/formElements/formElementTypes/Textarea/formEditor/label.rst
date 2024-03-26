.. include:: /Includes.rst.txt
formEditor.label
----------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.formElementsDefinition.Textarea.formEditor.label

:aspect:`Data type`
      string

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      Yes

:aspect:`Default value (for prototype 'standard')`
      .. code-block:: yaml
         :linenos:
         :emphasize-lines: 3-

         Textarea:
           formEditor:
             label: formEditor.elements.Textarea.label

:aspect:`Good to know`
      - :ref:`"Translate form editor settings"<concepts-formeditor-translation-formeditor>`

:aspect:`Description`
      This label will be shown within the "new element" Modal.
