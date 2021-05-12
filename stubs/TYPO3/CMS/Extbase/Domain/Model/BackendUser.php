<?php


namespace TYPO3\CMS\Extbase\Domain\Model;

if (class_exists(BackendUser::class)) {
    return;
}

class BackendUser
{
    public function getUid(): int
    {
        return 1;
    }
}
