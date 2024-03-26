<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 project.
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

namespace TYPO3\CMS\Composer\Plugin\Core;

use Composer\Script\Event;

/**
 * Contract for script registration for execution during composer build time
 *
 * @author Helmut Hummel <info@helhum.io>
 */
interface InstallerScriptsRegistration
{
    /**
     * Allows to register one or more script objects that implement this interface
     * This will be called in the Plugin right before the scripts are executed.
     *
     * @param Event $event
     * @param ScriptDispatcher $scriptDispatcher
     * @return void
     */
    public static function register(Event $event, ScriptDispatcher $scriptDispatcher);
}
