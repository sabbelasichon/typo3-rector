<?php
namespace TYPO3\CMS\Core\Type;

if (class_exists('TYPO3\CMS\Core\Type\ContextualFeedbackSeverity')) {
    return;
}

class ContextualFeedbackSeverity
{
    const NOTICE = -2;
    const INFO = -1;
    const OK = 0;
    const WARNING = 1;
    const ERROR = 2;
}
