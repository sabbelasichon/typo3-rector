<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Security\Exception;

if (class_exists(SyntacticallyWrongRequestHashException::class)) {
    return;
}

final class SyntacticallyWrongRequestHashException
{

}
