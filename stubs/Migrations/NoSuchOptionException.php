<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Configuration\Exception;

if (class_exists(NoSuchOptionException::class)) {
    return;
}

final class NoSuchOptionException
{

}
