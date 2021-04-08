<?php
declare(strict_types=1);


namespace TYPO3\CMS\Core\TimeTracker;

if (class_exists(NullTimeTracker::class)) {
    return;
}

final class NullTimeTracker
{

}
