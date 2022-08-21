<?php
namespace TYPO3\CMS\Reports;

use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;

if (class_exists('TYPO3\CMS\Reports\Status')) {
    return;
}

class Status
{
    const NOTICE = -2;
    const INFO = -1;
    const OK = 0;
    const WARNING = 1;
    const ERROR = 2;

    private int $severity;

    public function __construct($severity = ContextualFeedbackSeverity::OK)
    {
        $this->severity = $severity;
    }

    public function getSeverity(): int
    {
        return $this->severity;
    }
}
