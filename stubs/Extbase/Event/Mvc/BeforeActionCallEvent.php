<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Mvc\Controller;

if (class_exists(BeforeActionCallEvent::class)) {
    return;
}

class BeforeActionCallEvent
{
}
