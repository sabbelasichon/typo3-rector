<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Mail;

if (class_exists(MailMessage::class)) {
    return;
}

final class MailMessage
{
    public function setSubject(string $subject): self
    {
        return $this;
    }

    public function setFrom($addresses, $name = null): self
    {
        return $this;
    }
    public function setTo($addresses, $name = null): self
    {
        return $this;
    }

    public function setBody($body, $contentType = null, $charset = null): self
    {
        return $this;
    }

    public function text($body, string $charset = 'utf-8')
    {
        return $this;
    }

    public function html($body, string $charset = 'utf-8')
    {
        return $this;
    }

    public function addPart(string $body, $contentType = null, $charset = null): self
    {
        return $this;
    }

    public function attach($attachment): self
    {
        return $this;
    }

    public function embed($embed): self
    {
        return $this;
    }

    public function embedFromPath($embed): self
    {
        return $this;
    }

    public function attachFromPath(string $path, string $name = null, string $contentType = null): self
    {
        return $this;
    }

    public function send(): void
    {

    }
}
