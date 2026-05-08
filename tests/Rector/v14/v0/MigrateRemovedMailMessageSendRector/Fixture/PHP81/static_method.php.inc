<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateRemovedMailMessageSendRector\Fixture;

use TYPO3\CMS\Core\Mail\MailMessage;

class MyStaticClass
{
    public static function sendMail(): void
    {
        $email = new MailMessage();
        $email->setSubject('Some subject');
        $email->send();
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateRemovedMailMessageSendRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Mail\MailMessage;

class MyStaticClass
{
    public static function sendMail(): void
    {
        $email = new MailMessage();
        $email->setSubject('Some subject');
        GeneralUtility::makeInstance(MailerInterface::class)->send($email);
    }
}

?>
