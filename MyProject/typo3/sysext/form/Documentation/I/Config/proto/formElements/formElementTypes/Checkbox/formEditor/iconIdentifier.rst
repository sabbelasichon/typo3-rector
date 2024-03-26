.. include:: /Includes.rst.txt
formEditor.iconIdentifier
-------------------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.formElementsDefinition.Checkbox.formEditor.iconIdentifier

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

         Checkbox:
           formEditor:
             iconIdentifier: form-checkbox

.. :aspect:`Good to know`
      ToDo

:aspect:`Description`
      An icon identifier which must be registered through the :php:`\TYPO3\CMS\Core\Imaging\IconRegistry`.
      This icon will be shown within

      - :ref:`"Inspector [FormElementHeaderEditor]"<prototypes.\<prototypeidentifier>.formelementsdefinition.\<formelementtypeidentifier>.formeditor.editors.*.formelementheadereditor>`.
      - :ref:`"Abstract view formelement templates"<apireference-formeditor-stage-commonabstractformelementtemplates>`.
      - ``Tree`` component.
      - "new element" Modal
