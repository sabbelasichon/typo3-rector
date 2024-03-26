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
 * Validator for integers.
 */
final class IntegerValidator extends AbstractValidator
{
    /**
     * Checks if the given value is a valid integer.
     */
    public function isValid(mixed $value): void
    {
        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            $this->addError(
                $this->translateErrorMessage(
                    'validator.integer.notvalid',
                    'extbase'
                ),
                1221560494
            );
        }
    }
}
