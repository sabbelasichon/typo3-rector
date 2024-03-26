.. include:: /Includes.rst.txt
properties.containerClassAttribute
----------------------------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.formElementsDefinition.Hidden.properties.containerClassAttribute

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
         :emphasize-lines: 3

         Hidden:
           properties:
             containerClassAttribute: input
             elementClassAttribute: ''
             elementErrorClassAttribute: error

.. :aspect:`Good to know`
      ToDo

:aspect:`Description`
      A CSS class which is typically wrapped around the form elements.
