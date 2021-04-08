<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Log;

if(class_exists(LogManager::class)) {
    return null;
}

final class LogManager
{
    public function getLogger(string $class): Logger
    {
        return new Logger();
    }
}
