<?php

namespace TYPO3\CMS\Extbase\DomainObject;

if (class_exists('TYPO3\CMS\Extbase\DomainObject\AbstractEntity')) {
    return;
}

abstract class AbstractEntity extends AbstractDomainObject
{
}
