<?php

namespace TYPO3\CMS\Core\Messaging;

use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;

if (class_exists('TYPO3\CMS\Core\Messaging\AbstractMessage')) {
    return;
}

abstract class AbstractMessage implements \JsonSerializable
{
    const NOTICE = -2;
    const INFO = -1;
    const OK = 0;
    const WARNING = 1;
    const ERROR = 2;

    private ContextualFeedbackSeverity $severity;

    public function setSeverity($severity = ContextualFeedbackSeverity::OK): void
    {
        $this->severity = $severity;
    }

    public function getSeverity(): ContextualFeedbackSeverity
    {
        return $this->severity;
    }
}
