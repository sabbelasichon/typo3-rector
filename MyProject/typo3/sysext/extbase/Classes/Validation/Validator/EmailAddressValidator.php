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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Validator for email addresses
 */
final class EmailAddressValidator extends AbstractValidator
{
    /**
     * Checks if the given value is a valid email address.
     */
    public function isValid(mixed $value): void
    {
        if (!is_string($value) || !$this->validEmail($value)) {
            $this->addError(
                $this->translateErrorMessage(
                    'validator.emailaddress.notvalid',
                    'extbase'
                ),
                1221559976
            );
        }
    }

    /**
     * Checking syntax of input email address
     *
     * @param string $emailAddress Input string to evaluate
     * @return bool Returns TRUE if the $email address (input string) is valid
     */
    protected function validEmail(string $emailAddress): bool
    {
        return GeneralUtility::validEmail($emailAddress);
    }
}
