<?php
namespace TYPO3\CMS\Extbase\DomainObject;

if (class_exists('TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject')) {
    return;
}

class AbstractDomainObject implements DomainObjectInterface
{
    public function __wakeup()
    {

    }
}
