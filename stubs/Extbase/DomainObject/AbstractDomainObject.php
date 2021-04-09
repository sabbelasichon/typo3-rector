<?php
declare(strict_types=1);

namespace TYPO3\CMS\Extbase\DomainObject;

if (class_exists(AbstractDomainObject::class)) {
    return;
}

class AbstractDomainObject
{
    public function __wakeup()
    {

    }
}
