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

namespace TYPO3\CMS\FrontendLogin\Configuration;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * A class that holds and manages all states relevant for handling redirects
 *
 * @internal this is a concrete TYPO3 implementation and solely used for EXT:felogin and not part of TYPO3's Core API.
 */
class RedirectConfiguration
{
    protected array $modes;

    public function __construct(
        array|string|null $mode,
        protected string $firstMode,
        protected int $pageOnLogin,
        protected string $domains,
        protected int $pageOnLoginError,
        protected int $pageOnLogout
    ) {
        $this->modes = is_array($mode) ? $mode : GeneralUtility::trimExplode(',', $mode ?? '', true);
    }

    public function getModes(): array
    {
        return $this->modes;
    }

    public function getFirstMode(): string
    {
        return $this->firstMode;
    }

    public function getPageOnLogin(): int
    {
        return $this->pageOnLogin;
    }

    public function getDomains(): string
    {
        return $this->domains;
    }

    public function getPageOnLoginError(): int
    {
        return $this->pageOnLoginError;
    }

    public function getPageOnLogout(): int
    {
        return $this->pageOnLogout;
    }

    /**
     * Factory when creating a configuration out of Extbase / plugin settings.
     */
    public static function fromSettings(array $settings): self
    {
        return new RedirectConfiguration(
            ($settings['redirectMode'] ?? ''),
            (string)($settings['redirectFirstMethod'] ?? ''),
            (int)($settings['redirectPageLogin'] ?? 0),
            (string)($settings['domains'] ?? ''),
            (int)($settings['redirectPageLoginError'] ?? 0),
            (int)($settings['redirectPageLogout'] ?? 0)
        );
    }
}
