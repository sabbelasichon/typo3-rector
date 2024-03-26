.. include:: /Includes.rst.txt

formEditor
----------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.formElementsDefinition.StaticText.formEditor

:aspect:`Data type`
      array

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      Recommended

:aspect:`Default value (for prototype 'standard')`
      .. code-block:: yaml
         :linenos:
         :emphasize-lines: 2-

         StaticText:
           formEditor:
             editors:
               100:
                 identifier: header
                 templateName: Inspector-FormElementHeaderEditor
               200:
                 identifier: label
                 templateName: Inspector-TextEditor
                 label: formEditor.elements.ReadOnlyFormElement.editor.label.label
                 propertyPath: label
               300:
                 identifier: staticText
                 templateName: Inspector-TextareaEditor
                 label: formEditor.elements.StaticText.editor.staticText.label
                 propertyPath: properties.text
               9999:
                 identifier: removeButton
                 templateName: Inspector-RemoveElementEditor
             predefinedDefaults:
               properties:
                 text: ''
             label: formEditor.elements.StaticText.label
             group: custom
             groupSorting: 600
             iconIdentifier: form-static-text
