.. include:: /Includes.rst.txt
formEditor.propertyCollections.validators.40.identifier
-------------------------------------------------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.formElementsDefinition.Date.formEditor.propertyCollections.validators.40.identifier

:aspect:`Data type`
      string

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      Yes

:aspect:`Default value (for prototype 'standard')`
      .. code-block:: yaml
         :linenos:
         :emphasize-lines: 6

         Date:
           formEditor:
             propertyCollections:
               validators:
                 40:
                   identifier: EmailAddress

:aspect:`Good to know`
      - :ref:`"Inspector"<concepts-formeditor-inspector>`
      - :ref:`"\<validatorIdentifier>"<prototypes.\<prototypeidentifier>.validatorsdefinition.\<validatoridentifier>>`

:aspect:`Description`
      Identifies the validator which should be attached to the form element. Must be equal to an existing ``<validatorIdentifier>``.
