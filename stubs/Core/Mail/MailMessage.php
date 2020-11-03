<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Mail;

if (class_exists(MailMessage::class)) {
    return;
}

final class MailMessage
{
    public function send(): void
    {

    }
}
