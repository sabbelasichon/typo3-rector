<?php
namespace TYPO3\CMS\Core\Context;

if (class_exists('TYPO3\CMS\Core\Context\UserAspect')) {
    return;
}

class UserAspect
{
    public function isUserOrGroupSet(): bool
    {
        return true;
    }
}
