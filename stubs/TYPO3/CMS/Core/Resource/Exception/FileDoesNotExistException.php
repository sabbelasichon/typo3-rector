<?php
namespace TYPO3\CMS\Core\Resource\Exception;

use Exception;

if (class_exists('TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException')) {
    return;
}

class FileDoesNotExistException extends Exception
{

}
