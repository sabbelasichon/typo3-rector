.. include:: /Includes.rst.txt


.. _prototypes.<prototypeidentifier>.finishersdefinition.closure:

=========
[Closure]
=========


.. _prototypes.<prototypeidentifier>.finishersdefinitionclosure-properties:

Properties
==========


.. _prototypes.<prototypeIdentifier>.finishersdefinition.closure.implementationclassname:

implementationClassName
-----------------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.finishersDefinition.Closure.implementationClassName

:aspect:`Data type`
      string

:aspect:`Needed by`
      Frontend

:aspect:`Mandatory`
      Yes

:aspect:`Default value`
      .. code-block:: yaml
         :linenos:
         :emphasize-lines: 2

         Closure:
           implementationClassName: TYPO3\CMS\Form\Domain\Finishers\ClosureFinisher

:aspect:`Good to know`
      - :ref:`"Custom finisher implementations"<concepts-finishers-customfinisherimplementations>`

:aspect:`Description`
      .. include:: ../properties/implementationClassName.rst


.. _prototypes.<prototypeIdentifier>.finishersdefinition.closure.options.closure:

options.closure
---------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.finishersDefinition.Closure.options.closure

:aspect:`Data type`
      \Closure

:aspect:`Needed by`
      Frontend

:aspect:`Mandatory`
      Yes

:aspect:`Default value`
      null

.. :aspect:`Good to know`
      ToDo
      - :ref:`"Closure finisher"<apireference-finisheroptions-closurefinisher>`
      - :ref:`"Custom finisher implementations"<concepts-finishers-customfinisherimplementations>`

:aspect:`Description`
      The closure which is invoked if the finisher is triggered.


.. _prototypes.<prototypeIdentifier>.finishersdefinition.closure.formeditor.iconidentifier:

formeditor.iconIdentifier
-------------------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.finishersDefinition.Closure.formEditor.iconIdentifier

:aspect:`Data type`
      string

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      Yes

:aspect:`Default value`
      .. code-block:: yaml
         :linenos:
         :emphasize-lines: 3

         Closure:
           formEditor:
             iconIdentifier: form-finisher
             label: formEditor.elements.Form.finisher.Closure.editor.header.label
             predefinedDefaults:
               options:
                 closure: ''

.. :aspect:`Good to know`
      ToDo

:aspect:`Description`
      .. include:: ../properties/iconIdentifier.rst


.. _prototypes.<prototypeIdentifier>.finishersdefinition.closure.formeditor.label:

formeditor.label
----------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.finishersDefinition.Closure.formEditor.label

:aspect:`Data type`
      string

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      Yes

:aspect:`Default value`
      .. code-block:: yaml
         :linenos:
         :emphasize-lines: 4

         Closure:
           formEditor:
             iconIdentifier: form-finisher
             label: formEditor.elements.Form.finisher.Closure.editor.header.label
             predefinedDefaults:
               options:
                 closure: ''

:aspect:`Good to know`
      - :ref:`"Translate form editor settings"<concepts-formeditor-translation-formeditor>`

:aspect:`Description`
      .. include:: ../properties/label.rst


.. _prototypes.<prototypeIdentifier>.finishersdefinition.closure.formeditor.predefineddefaults:

formeditor.predefinedDefaults
-----------------------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.finishersDefinition.Closure.formEditor.predefinedDefaults

:aspect:`Data type`
      array

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      No

:aspect:`Default value`
      .. code-block:: yaml
         :linenos:
         :emphasize-lines: 5-

         Closure:
           formEditor:
             iconIdentifier: form-finisher
             label: formEditor.elements.Form.finisher.Closure.editor.header.label
             predefinedDefaults:
               options:
                 closure: ''

.. :aspect:`Good to know`
      ToDo

:aspect:`Description`
      .. include:: ../properties/predefinedDefaults.rst
