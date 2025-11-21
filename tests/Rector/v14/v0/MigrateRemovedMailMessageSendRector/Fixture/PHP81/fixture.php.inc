<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateRemovedMailMessageSendRector\Fixture;

use TYPO3\CMS\Core\Mail\MailMessage;

class MyClass
{
    public function sendMail(): void
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

use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Mail\MailMessage;

class MyClass
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }
    public function sendMail(): void
    {
        $email = new MailMessage();
        $email->setSubject('Some subject');
        $this->mailer->send($email);
    }
}

?>
