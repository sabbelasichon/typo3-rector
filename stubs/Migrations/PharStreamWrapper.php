<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\IO;

if (class_exists(PharStreamWrapper::class)) {
    return;
}

final class PharStreamWrapper
{

}
