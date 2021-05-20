<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\TimeTracker;

if (class_exists('TYPO3\CMS\Core\TimeTracker\TimeTracker')) {
    return;
}

class TimeTracker
{
    public function __construct($isEnabled = true)
    {

    }

    public function setTSlogMessage($content, $num = 0): void
    {
    }
}
