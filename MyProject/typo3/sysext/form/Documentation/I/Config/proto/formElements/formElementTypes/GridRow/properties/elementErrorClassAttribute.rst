.. include:: /Includes.rst.txt
properties.elementErrorClassAttribute
-------------------------------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.formElementsDefinition.GridRow.properties.elementErrorClassAttribute

:aspect:`Data type`
      string

:aspect:`Needed by`
      Frontend/ Backend (form editor)

:aspect:`Overwritable within form definition`
      Yes

:aspect:`form editor can write this property into the form definition (for prototype 'standard')`
      No

:aspect:`Mandatory`
      No

:aspect:`Default value (for prototype 'standard')`
      .. code-block:: yaml
         :linenos:
         :emphasize-lines: 5

         GridRow:
           properties:
             containerClassAttribute: input
             elementClassAttribute: row
             elementErrorClassAttribute: error
             gridColumnClassAutoConfiguration:
               gridSize: 12
               viewPorts:
                 xs:
                   classPattern: 'col-{@numbersOfColumnsToUse}'
                 sm:
                   classPattern: 'col-sm-{@numbersOfColumnsToUse}'
                 md:
                   classPattern: 'col-md-{@numbersOfColumnsToUse}'
                 lg:
                   classPattern: 'col-lg-{@numbersOfColumnsToUse}'

.. :aspect:`Good to know`
      ToDo

:aspect:`Description`
      A CSS class which is written to the form element if validation errors exists.
