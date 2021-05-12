<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\SendNotifyEmailToMailApiRector\Fixture;

class MyService
{
    public function init()
    {
        $GLOBALS['TSFE']->cObj->sendNotifyEmail("Subject\nMessage", 'max.mustermann@domain.com', 'max.mustermann@domain.com', 'max.mustermann@domain.com');
    }

    public function send()
    {
        if($GLOBALS['TSFE']->cObj->sendNotifyEmail("Subject\nMessage", 'max.mustermann@domain.com', 'max.mustermann@domain.com', 'max.mustermann@domain.com')) {
            //
        }
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\SendNotifyEmailToMailApiRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Mail\MailMessage;
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Utility\MailUtility;
class MyService
{
    public function init()
    {
        $success = false;
        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $message = trim("Subject\nMessage");
        $senderName = trim(null);
        $senderAddress = trim('max.mustermann@domain.com');
        if ($senderAddress !== '') {
            $mail->from(new Address($senderAddress, $senderName));
        }
        if ($message !== '') {
            $messageParts = explode(LF, $message, 2);
            $subject = trim($messageParts[0]);
            $plainMessage = trim($messageParts[1]);
            $parsedRecipients = MailUtility::parseAddresses('max.mustermann@domain.com');
            if (!empty($parsedRecipients)) {
                $mail->to(...$parsedRecipients)->subject($subject)->text($plainMessage);
                $mail->send();
            }
            $success = true;
        }
        $success;
    }

    public function send()
    {
        $success = false;
        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $message = trim("Subject\nMessage");
        $senderName = trim(null);
        $senderAddress = trim('max.mustermann@domain.com');
        if ($senderAddress !== '') {
            $mail->from(new Address($senderAddress, $senderName));
        }
        if ($message !== '') {
            $messageParts = explode(LF, $message, 2);
            $subject = trim($messageParts[0]);
            $plainMessage = trim($messageParts[1]);
            $parsedRecipients = MailUtility::parseAddresses('max.mustermann@domain.com');
            if (!empty($parsedRecipients)) {
                $mail->to(...$parsedRecipients)->subject($subject)->text($plainMessage);
                $mail->send();
            }
            $success = true;
        }
        if($success) {
            //
        }
    }
}

?>
