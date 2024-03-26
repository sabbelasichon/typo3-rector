.. include:: /Includes.rst.txt
properties.gridColumnClassAutoConfiguration.gridSize
----------------------------------------------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.formElementsDefinition.GridRow.properties.gridColumnClassAutoConfiguration.gridSize

:aspect:`Data type`
      int

:aspect:`Needed by`
      Frontend/ Backend (form editor)

:aspect:`Overwritable within form definition`
      Yes

:aspect:`form editor can write this property into the form definition (for prototype 'standard')`
      No

:aspect:`Mandatory`
      Yes

:aspect:`Default value (for prototype 'standard')`
      .. code-block:: yaml
         :linenos:
         :emphasize-lines: 7

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
      The grid size of the CSS grid system (bootstrap by default).
