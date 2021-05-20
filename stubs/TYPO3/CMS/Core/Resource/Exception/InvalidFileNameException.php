<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Resource\Exception;

use Exception;

if (class_exists('TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException')) {
    return;
}

class InvalidFileNameException extends Exception
{

}
