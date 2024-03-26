.. include:: /Includes.rst.txt
properties.elementClassAttribute
--------------------------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.formElementsDefinition.Textarea.properties.elementClassAttribute

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
         :emphasize-lines: 4

         Textarea:
           properties:
             containerClassAttribute: input
             elementClassAttribute: xxlarge
             elementErrorClassAttribute: error

.. :aspect:`Good to know`
      ToDo

:aspect:`Description`
      A CSS class written to the form element.
