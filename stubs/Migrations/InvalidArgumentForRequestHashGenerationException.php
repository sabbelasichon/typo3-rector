<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Security\Exception;

if (class_exists(InvalidArgumentForRequestHashGenerationException::class)) {
    return;
}

final class InvalidArgumentForRequestHashGenerationException
{

}
