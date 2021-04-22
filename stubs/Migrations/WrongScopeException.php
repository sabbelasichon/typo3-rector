<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Object\Exception;

if (class_exists(WrongScopeException::class)) {
    return;
}

final class WrongScopeException
{

}
