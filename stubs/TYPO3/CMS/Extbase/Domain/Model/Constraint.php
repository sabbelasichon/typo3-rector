<?php

namespace TYPO3\CMS\Extbase\Domain\Model;

class Constraint
{
    public function getStartTimestamp(): int
    {
        return 1;
    }

    public function getEndTimestamp(): int
    {
        return 2;
    }

    public function getNumber(): int
    {
        return 42;
    }
}
