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

use TYPO3\CMS\Extbase\Error\Result;

/**
 * Validator to chain many validators in a disjunction (logical or).
 */
final class DisjunctionValidator extends AbstractCompositeValidator
{
    public function __construct()
    {
        $this->validators = new \SplObjectStorage();
        $this->validatedInstancesContainer = new \SplObjectStorage();
    }

    /**
     * Checks if the given value is valid according to the validators of the
     * disjunction.
     *
     * So only one validator has to be valid, to make the whole disjunction valid.
     * Errors are only returned if all validators failed.
     *
     * @param mixed $value The value that should be validated
     */
    public function validate(mixed $value): Result
    {
        $validators = $this->getValidators();
        if ($validators->count() > 0) {
            $result = null;
            foreach ($validators as $validator) {
                $validatorResult = $validator->validate($value);
                if ($validatorResult->hasErrors()) {
                    if ($result === null) {
                        $result = $validatorResult;
                    } else {
                        $result->merge($validatorResult);
                    }
                } else {
                    if ($result === null) {
                        $result = $validatorResult;
                    } else {
                        $result->clear();
                    }
                    break;
                }
            }
        } else {
            $result = new Result();
        }

        return $result;
    }
}
