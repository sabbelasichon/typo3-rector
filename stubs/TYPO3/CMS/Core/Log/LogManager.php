<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Log;

if(class_exists('TYPO3\CMS\Core\Log\LogManager')) {
    return null;
}

class LogManager
{
    public function getLogger(string $class): Logger
    {
        return new Logger();
    }
}
