<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Resource\Security;

if (class_exists(FileNameValidator::class)) {
    return;
}

final class FileNameValidator
{
    /**
     * Previously this was used within SystemEnvironmentBuilder
     */
    public const DEFAULT_FILE_DENY_PATTERN = '\\.(php[3-8]?|phpsh|phtml|pht|phar|shtml|cgi)(\\..*)?$|\\.pl$|^\\.htaccess$';

}
