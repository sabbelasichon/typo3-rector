<?php


namespace TYPO3\CMS\Extbase\Validation\Exception;

if (class_exists(NoValidatorFoundException::class)) {
    return;
}

class NoValidatorFoundException
{

}
