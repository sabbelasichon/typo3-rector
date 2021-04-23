<?php
declare(strict_types=1);


namespace TYPO3\CMS\Core\Http;

use Exception;

if(class_exists(ImmediateResponseException::class)) {
    return;
}

class ImmediateResponseException extends Exception
{

}
