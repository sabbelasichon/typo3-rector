<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v0\SwiftMailerBasedMailMessageToMailerBasedMessage\Fixture;

use Swift_Attachment;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$mail = GeneralUtility::makeInstance(MailMessage::class);

$mail->setSubject('Your subject');
$mail->setFrom(['john@doe.com' => 'John Doe']);
$mail->setTo(['receiver@domain.org', 'other@domain.org' => 'A name']);
$mail->setBody('Here is the message itself');
$mail->addPart('<p>Here is the message itself</p>', 'text/html');
$mail->attach(Swift_Attachment::fromPath('my-document.pdf'));
$mail->send();

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v0\SwiftMailerBasedMailMessageToMailerBasedMessage\Fixture;

use Swift_Attachment;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$mail = GeneralUtility::makeInstance(MailMessage::class);

$mail->setSubject('Your subject');
$mail->setFrom(['john@doe.com' => 'John Doe']);
$mail->setTo(['receiver@domain.org', 'other@domain.org' => 'A name']);
$mail->text('Here is the message itself');
$mail->html('<p>Here is the message itself</p>');
$mail->attachFromPath('my-document.pdf');
$mail->send();

?>
