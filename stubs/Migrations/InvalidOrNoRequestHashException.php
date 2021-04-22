<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Mvc\Exception;

if (class_exists(InvalidOrNoRequestHashException::class)) {
    return;
}

final class InvalidOrNoRequestHashException
{

}
