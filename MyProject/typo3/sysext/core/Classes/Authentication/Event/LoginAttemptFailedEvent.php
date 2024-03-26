<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Core\Authentication\Event;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Authentication\AbstractUserAuthentication;

/**
 * Event fired after a login attempt failed.
 */
final class LoginAttemptFailedEvent extends AbstractAuthenticationFailedEvent
{
    public function __construct(
        private readonly AbstractUserAuthentication $user,
        private readonly ServerRequestInterface $request,
        private readonly array $loginData,
    ) {
        parent::__construct($this->request);
    }

    public function getUser(): AbstractUserAuthentication
    {
        return $this->user;
    }

    public function getLoginData(): array
    {
        return $this->loginData;
    }
}
