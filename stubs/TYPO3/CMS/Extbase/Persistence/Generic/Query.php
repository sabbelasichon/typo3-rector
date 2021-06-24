<?php
declare(strict_types=1);


namespace TYPO3\CMS\Extbase\Persistence\Generic;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;

if (class_exists('TYPO3\CMS\Extbase\Persistence\Generic\Query')) {
    return;
}

final class Query implements QueryInterface
{

    public function logicalAnd($constraint1)
    {
    }

    public function logicalOr($constraint1)
    {
    }
}
