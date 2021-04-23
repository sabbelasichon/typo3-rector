<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Validation\Exception;

if (class_exists(NoValidatorFoundException::class)) {
    return;
}

class NoValidatorFoundException
{

}
