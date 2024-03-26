.. include:: /Includes.rst.txt
formEditor._isTopLevelFormElement
---------------------------------

:aspect:`Option path`
      prototypes.<prototypeIdentifier>.formElementsDefinition.Page.formEditor._isTopLevelFormElement

:aspect:`Data type`
      bool

:aspect:`Needed by`
      Backend (form editor)

:aspect:`Mandatory`
      Yes

:aspect:`Default value (for prototype 'standard')`
      .. code-block:: yaml
         :linenos:
         :emphasize-lines: 3-

         Page:
           formEditor:
             _isTopLevelFormElement: true

.. :aspect:`Good to know`
      ToDo

:aspect:`Description`
      Internal control setting to define that the form element must not have a parent form element.
