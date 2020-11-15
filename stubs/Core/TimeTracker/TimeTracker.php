<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\TimeTracker;

if (class_exists(TimeTracker::class)) {
    return;
}

final class TimeTracker
{
    public function __construct($isEnabled = true)
    {

    }

    public function setTSlogMessage($content, $num = 0): void
    {
    }
}
