<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v0\SwiftMailerBasedMailMessageToMailerBasedMessageRector\Fixture;

use Swift_Attachment;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$mail = GeneralUtility::makeInstance(MailMessage::class);

$mail
    ->setSubject('Your subject')
    ->setFrom(['john@doe.com' => 'John Doe'])
    ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
    ->setBody('Here is the message itself')
    ->addPart('<p>Here is the message itself</p>', 'text/html')
    ->attach(Swift_Attachment::fromPath('my-document.pdf'))
    ->send();

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v0\SwiftMailerBasedMailMessageToMailerBasedMessageRector\Fixture;

use Swift_Attachment;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$mail = GeneralUtility::makeInstance(MailMessage::class);

$mail
    ->setSubject('Your subject')
    ->setFrom(['john@doe.com' => 'John Doe'])
    ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
    ->text('Here is the message itself')
    ->html('<p>Here is the message itself</p>')
    ->attachFromPath('my-document.pdf')
    ->send();

?>
