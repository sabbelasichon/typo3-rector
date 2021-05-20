<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Domain\Model;

if (class_exists('TYPO3\CMS\Extbase\Domain\Model\BackendUser')) {
    return;
}

class BackendUser
{
    public function getUid(): int
    {
        return 1;
    }
}
