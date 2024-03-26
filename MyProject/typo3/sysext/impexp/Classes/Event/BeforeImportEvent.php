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

namespace TYPO3\CMS\Impexp\Event;

use TYPO3\CMS\Impexp\Import;

/**
 * This event is triggered when an import file is about to be imported
 */
final class BeforeImportEvent
{
    public function __construct(
        private readonly Import $import,
        private readonly string $file
    ) {}

    public function getImport(): Import
    {
        return $this->import;
    }

    /**
     * The file being about to be imported
     */
    public function getFile(): string
    {
        return $this->file;
    }
}
