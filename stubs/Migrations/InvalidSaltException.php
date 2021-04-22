<?php

declare(strict_types=1);

namespace TYPO3\CMS\Saltedpasswords\Exception;

if (class_exists(InvalidSaltException::class)) {
    return;
}

final class InvalidSaltException
{

}
