<?php

namespace Symfony\Component\Mailer;

use Symfony\Component\Mime\RawMessage;

if (interface_exists('Symfony\Component\Mailer\MailerInterface')) {
    return;
}

interface MailerInterface
{
    public function send(RawMessage $message, ?Envelope $envelope = null): void;
}
