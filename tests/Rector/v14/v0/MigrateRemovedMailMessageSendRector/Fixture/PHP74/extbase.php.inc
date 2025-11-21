<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateRemovedMailMessageSendRector\Fixture\PHP74;

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

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateRemovedMailMessageSendRector\Fixture\PHP74;

use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ExtbaseClass extends ActionController
{
    private MailerInterface $mailer;
    /**
     * AbstractController constructor.
     */
    public function __construct(MailerInterface $mailer)
    {
        parent::__construct();
        $this->mailer = $mailer;
    }
    public function sendMail(): void
    {
        $email = new MailMessage();
        $email->setSubject('Some subject');
        $this->mailer->send($email);
    }
}

?>
