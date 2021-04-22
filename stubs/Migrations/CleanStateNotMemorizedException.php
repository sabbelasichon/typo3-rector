<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Persistence\Generic\Exception;

if (class_exists(CleanStateNotMemorizedException::class)) {
    return;
}

final class CleanStateNotMemorizedException
{

}
