
finishers:
  -
    options:
      recipients:
        bar@domain.com: 'Bar'
      subject: 'Kontaktanfrage von Website'
      recipientAddress: foo@domain.com
      recipientName: Stadtwerke Bonn
      replyToAddress: foo@domain.com
      senderAddress: '{email}'
      senderName: '{lastName}'
      carbonCopyAddress: foo@domain.com
      blindCarbonCopyAddress: foo@domain.com
      format: html
      attachUploads: true
    identifier: EmailToReceiver
  -
    options:
      recipients:
        bar@domain.com: 'Bar'
      subject: 'Kontaktanfrage von Website'
      recipientAddress: foo@domain.com
      recipientName: Stadtwerke Bonn
      replyToAddress: foo@domain.com
      senderAddress: '{email}'
      senderName: '{lastName}'
      carbonCopyAddress: foo@domain.com
      blindCarbonCopyAddress: foo@domain.com
      format: html
      attachUploads: true
    identifier: EmailToSender
variants:
  -
    identifier: variant-1
    condition: 'formValues["foo"] == "bar"'
    finishers:
      -
        identifier: EmailToReceiver
        options:
          subject: 'Example Subject'
          recipientAddress: user@example.org
          recipientName: 'Example Recipient'
          senderAddress: no-reply@example.org
          senderName: '{text-2} {text-1}'
          replyToAddress: '{email}'
-----
finishers:
  -
    options:
      recipients:
        foo@domain.com: 'Stadtwerke Bonn'
        bar@domain.com: Bar
      subject: 'Kontaktanfrage von Website'
      senderAddress: '{email}'
      senderName: '{lastName}'
      format: html
      attachUploads: true
      replyToRecipients:
        - foo@domain.com
      carbonCopyRecipients:
        - foo@domain.com
      blindCarbonCopyRecipients:
        - foo@domain.com
    identifier: EmailToReceiver
  -
    options:
      recipients:
        foo@domain.com: 'Stadtwerke Bonn'
        bar@domain.com: Bar
      subject: 'Kontaktanfrage von Website'
      senderAddress: '{email}'
      senderName: '{lastName}'
      format: html
      attachUploads: true
      replyToRecipients:
        - foo@domain.com
      carbonCopyRecipients:
        - foo@domain.com
      blindCarbonCopyRecipients:
        - foo@domain.com
    identifier: EmailToSender
variants:
  -
    identifier: variant-1
    condition: 'formValues["foo"] == "bar"'
    finishers:
      -
        identifier: EmailToReceiver
        options:
          subject: 'Example Subject'
          senderAddress: no-reply@example.org
          senderName: '{text-2} {text-1}'
          replyToRecipients:
            - '{email}'
          recipients:
            user@example.org: 'Example Recipient'
