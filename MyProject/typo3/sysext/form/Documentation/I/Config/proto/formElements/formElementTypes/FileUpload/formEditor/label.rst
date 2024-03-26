.. include:: /Includes.rst.txt
formEditor.label
----------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.formElementsDefinition.FileUpload.formEditor.label

:aspect:`Data type`
      string

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      Yes

:aspect:`Default value (for prototype 'standard')`
      .. code-block:: yaml
         :linenos:
         :emphasize-lines: 3

         FileUpload:
           formEditor:
             label: formEditor.elements.FileUpload.label

:aspect:`Good to know`
      - :ref:`"Translate form editor settings"<concepts-formeditor-translation-formeditor>`

:aspect:`Description`
      This label will be shown within the "new element" Modal.
