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

namespace TYPO3\CMS\Extbase\Validation\Validator;

/**
 * Validator for alphanumeric strings
 */
final class AlphanumericValidator extends AbstractValidator
{
    /**
     * The given $value is valid if it is an alphanumeric string, which is defined as [\pL\d]*.
     */
    public function isValid(mixed $value): void
    {
        if (!is_string($value) || preg_match('/^[\pL\d]*$/u', $value) !== 1) {
            $this->addError($this->translateErrorMessage('validator.alphanumeric.notvalid', 'extbase'), 1221551320);
        }
    }
}
