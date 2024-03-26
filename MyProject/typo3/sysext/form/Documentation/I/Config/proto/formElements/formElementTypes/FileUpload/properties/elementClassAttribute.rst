.. include:: /Includes.rst.txt
properties.elementClassAttribute
--------------------------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.formElementsDefinition.FileUpload.properties.elementClassAttribute

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

         FileUpload:
           properties:
             containerClassAttribute: input
             elementClassAttribute: ''
             elementErrorClassAttribute: error
             saveToFileMount: '1:/user_upload/'
             allowedMimeTypes:
               - application/msword
               - application/vnd.openxmlformats-officedocument.wordprocessingml.document
               - application/vnd.oasis.opendocument.text
               - application/pdf

.. :aspect:`Good to know`
      ToDo

:aspect:`Description`
      A CSS class written to the form element.
