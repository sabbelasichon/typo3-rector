<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateRemovedMailMessageSendRector\Fixture\PHP81;

use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ExtbaseClass extends ActionController
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

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateRemovedMailMessageSendRector\Fixture\PHP81;

use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ExtbaseClass extends ActionController
{
    /**
     * AbstractController constructor.
     */
    public function __construct(private readonly MailerInterface $mailer)
    {
        parent::__construct();
    }
    public function sendMail(): void
    {
        $email = new MailMessage();
        $email->setSubject('Some subject');
        $this->mailer->send($email);
    }
}

?>
