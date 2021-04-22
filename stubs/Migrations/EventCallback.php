<?php

declare(strict_types=1);

namespace TYPO3\CMS\Version\Dependency;

if (class_exists(EventCallback::class)) {
    return;
}

final class EventCallback
{

}
