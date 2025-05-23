<?php

namespace TYPO3\CMS\Extbase\Persistence\Generic\Qom;

if (interface_exists('TYPO3\CMS\Extbase\Persistence\Generic\Qom\OrInterface')) {
    return;
}

interface OrInterface extends ConstraintInterface
{
}
