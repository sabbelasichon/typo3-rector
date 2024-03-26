.. include:: /Includes.rst.txt
formEditor.predefinedDefaults
-----------------------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.formElementsDefinition.SummaryPage.formEditor.predefinedDefaults

:aspect:`Data type`
      array

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      Recommended

:aspect:`Default value (for prototype 'standard')`
      .. code-block:: yaml
         :linenos:
         :emphasize-lines: 3-

         SummaryPage:
           formEditor:
             predefinedDefaults:
               renderingOptions:
                 previousButtonLabel: 'formEditor.elements.SummaryPage.editor.previousButtonLabel.value'
                 nextButtonLabel: 'formEditor.elements.SummaryPage.editor.nextButtonLabel.value'

.. :aspect:`Good to know`
      ToDo

:aspect:`Description`
      Defines predefined defaults for form element properties which are prefilled, if the form element is added to a form.
