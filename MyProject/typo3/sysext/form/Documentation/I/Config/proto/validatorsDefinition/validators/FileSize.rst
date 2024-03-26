.. include:: /Includes.rst.txt


.. _prototypes.<prototypeidentifier>.validatorsdefinition.filesize:

==========
[FileSize]
==========


.. _prototypes.<prototypeidentifier>.validatorsdefinition.filesize-validationerrorcodes:

Validation error codes
======================

- Error code: `1505303626`
- Error message: `You must enter an instance of \TYPO3\CMS\Extbase\Domain\Model\FileReference
  or \TYPO3\CMS\Core\Resource\File.`

- Error code: `1505305752`
- Error message: `You must select a file that is larger than %s in size.`

- Error code: `1505305753`
- Error message: `You must select a file that is no larger than %s.`


.. _prototypes.<prototypeidentifier>.validatorsdefinition.filesize-properties:

Properties
==========


.. _prototypes.<prototypeidentifier>.validatorsdefinition.filesize.implementationClassName:

implementationClassName
-----------------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.validatorsDefinition.FileSize.implementationClassName

:aspect:`Data type`
      string

:aspect:`Needed by`
      Frontend

:aspect:`Mandatory`
      Yes

:aspect:`Default value (for prototype 'standard')`
      .. code-block:: yaml
         :linenos:
         :emphasize-lines: 2

             FileSize:
               implementationClassName: TYPO3\CMS\Form\Mvc\Validation\FileSizeValidator

:aspect:`Good to know`
      - :ref:`"Custom validator implementations"<concepts-validators-customvalidatorimplementations>`

:aspect:`Description`
      .. include:: ../properties/implementationClassName.rst


.. _prototypes.<prototypeidentifier>.validatorsdefinition.filesize.options.minimum:

options.minimum
---------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.validatorsDefinition.FileSize.options.minimum

:aspect:`Data type`
      string

:aspect:`Needed by`
      Frontend

:aspect:`Mandatory`
      Yes

:aspect:`Default value (for prototype 'standard')`
      undefined

:aspect:`Description`
      The minimum filesize to accept. Use the format <size>B|K|M|G. For example: 10M means 10 Megabytes.


.. _prototypes.<prototypeidentifier>.validatorsdefinition.filesize.options.maximum:

options.maximum
---------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.validatorsDefinition.FileSize.options.maximum

:aspect:`Data type`
      string

:aspect:`Needed by`
      Frontend

:aspect:`Mandatory`
      Yes

:aspect:`Default value (for prototype 'standard')`
      undefined

:aspect:`Description`
      The maximum filesize to accept. Use the format <size>B|K|M|G. For example: 10M means 10 Megabytes.


.. _prototypes.<prototypeidentifier>.validatorsdefinition.filesize.formeditor.iconidentifier:

formEditor.iconIdentifier
-------------------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.validatorsDefinition.FileSize.formEditor.iconIdentifier

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

             FileSize:
               formEditor:
                 iconIdentifier: form-validator
                 label: formEditor.elements.FileUploadMixin.validators.FileSize.editor.header.label

.. :aspect:`Good to know`
      ToDo

:aspect:`Description`
      .. include:: ../properties/iconIdentifier.rst


.. _prototypes.<prototypeidentifier>.validatorsdefinition.filesize.formeditor.label:

formEditor.label
----------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.validatorsDefinition.FileSize.formEditor.label

:aspect:`Data type`
      string

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      Yes

:aspect:`Default value (for prototype 'standard')`
      .. code-block:: yaml
         :linenos:
         :emphasize-lines: 4

             FileSize:
               formEditor:
                 iconIdentifier: form-validator
                 label: formEditor.elements.FileUploadMixin.validators.FileSize.editor.header.label

:aspect:`Good to know`
      - :ref:`"Translate form editor settings"<concepts-formeditor-translation-formeditor>`

:aspect:`Description`
      .. include:: ../properties/label.rst


.. _prototypes.<prototypeidentifier>.validatorsdefinition.filesize.formeditor.predefineddefaults:

formEditor.predefinedDefaults
-----------------------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.validatorsDefinition.FileSize.formEditor.predefinedDefaults

:aspect:`Data type`
      array

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      No

:aspect:`Default value (for prototype 'standard')`
      .. code-block:: yaml
         :linenos:
         :emphasize-lines: 3-

             FileSize:
               formEditor:
                 predefinedDefaults:
                   options:
                     minimum: '0B'
                     maximum: '10M'

:aspect:`Description`
      .. include:: ../properties/predefinedDefaults.rst
