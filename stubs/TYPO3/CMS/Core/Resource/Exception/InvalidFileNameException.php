<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Resource\Exception;

use Exception;

if (class_exists(InvalidFileNameException::class)) {
    return;
}

class InvalidFileNameException extends Exception
{

}
