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

namespace TYPO3\CMS\T3editor;

use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;

/**
 * Represents a mode for CodeMirror
 * @internal
 */
class Mode
{
    protected JavaScriptModuleInstruction $module;

    /**
     * @var string
     */
    protected $formatCode = '';

    /**
     * @var array
     */
    protected $fileExtensions = [];

    /**
     * @var bool
     */
    protected $isDefault = false;

    public function __construct(JavaScriptModuleInstruction $module)
    {
        $this->module = $module;
    }

    public function getModule(): JavaScriptModuleInstruction
    {
        return $this->module;
    }

    public function getFormatCode(): string
    {
        return $this->formatCode;
    }

    public function setFormatCode(string $formatCode): Mode
    {
        $this->formatCode = $formatCode;

        return $this;
    }

    public function bindToFileExtensions(array $fileExtensions): Mode
    {
        $this->fileExtensions = $fileExtensions;

        return $this;
    }

    public function getBoundFileExtensions(): array
    {
        return $this->fileExtensions;
    }

    public function setAsDefault(): Mode
    {
        $this->isDefault = true;

        return $this;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }
}
