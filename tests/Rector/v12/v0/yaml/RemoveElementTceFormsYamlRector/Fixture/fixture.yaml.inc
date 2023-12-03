TYPO3:
  CMS:
    Form:
      prototypes:
        standard:
          finishersDefinition:
            EmailToReceiver:
              FormEngine:
                elements:
                  recipients:
                    el:
                      _arrayContainer:
                        el:
                          email:
                            TCEforms:
                              label: tt_content.finishersDefinition.EmailToSender.recipients.email.label
                              config:
                                type: input
-----
TYPO3:
  CMS:
    Form:
      prototypes:
        standard:
          finishersDefinition:
            EmailToReceiver:
              FormEngine:
                elements:
                  recipients:
                    el:
                      _arrayContainer:
                        el:
                          email:
                            label: tt_content.finishersDefinition.EmailToSender.recipients.email.label
                            config:
                              type: input
