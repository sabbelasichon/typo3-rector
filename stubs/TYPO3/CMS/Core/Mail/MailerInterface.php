<?php

namespace TYPO3\CMS\Core\Mail;

if (interface_exists('TYPO3\CMS\Core\Mail\MailerInterface')) {
    return;
}

interface MailerInterface extends \Symfony\Component\Mailer\MailerInterface
{
}
