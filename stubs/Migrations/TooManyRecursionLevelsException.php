<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Object\Container\Exception;

if (class_exists(TooManyRecursionLevelsException::class)) {
    return;
}

final class TooManyRecursionLevelsException
{

}
