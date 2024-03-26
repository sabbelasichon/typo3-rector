<?php

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

namespace TYPO3\CMS\Core\Core;

/**
 * The base ApplicationInterface which
 * is used for all Entry Points for TYPO3, may it be
 * Frontend, Backend, Install Tool or Command Line.
 * @internal only to be meant for internal Application-level purposes, not part of TYPO3 Core API.
 */
interface ApplicationInterface
{
    /**
     * Starting point
     */
    public function run();
}
