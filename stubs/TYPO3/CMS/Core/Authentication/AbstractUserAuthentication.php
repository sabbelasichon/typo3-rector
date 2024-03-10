<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Authentication;

abstract class AbstractUserAuthentication
{
    /**
     * @var array|null contains user- AND session-data from database (joined tables)
     */
    public ?array $user = null;
}
