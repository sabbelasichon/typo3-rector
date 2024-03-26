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

namespace TYPO3\CMS\Core\Resource;

use TYPO3\CMS\Core\Type\Enumeration;

/**
 * Enumeration object for DuplicationBehavior
 */
final class DuplicationBehavior extends Enumeration
{
    public const __default = self::CANCEL;

    /**
     * If a file is uploaded and another file with
     * the same name already exists, the new file
     * is renamed.
     */
    public const RENAME = 'rename';

    /**
     * If a file is uploaded and another file with
     * the same name already exists, the old file
     * gets overwritten by the new file.
     */
    public const REPLACE = 'replace';

    /**
     * If a file is uploaded and another file with
     * the same name already exists, the process is
     * aborted.
     */
    public const CANCEL = 'cancel';
}
