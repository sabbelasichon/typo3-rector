<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Configuration\Exception;

if (class_exists(ContainerIsLockedException::class)) {
    return;
}

class ContainerIsLockedException
{

}
