<?php
declare(strict_types=1);


namespace TYPO3\CMS\Core\Http;

use Exception;

if(class_exists('TYPO3\CMS\Core\Http\ImmediateResponseException')) {
    return;
}

class ImmediateResponseException extends Exception
{

}
