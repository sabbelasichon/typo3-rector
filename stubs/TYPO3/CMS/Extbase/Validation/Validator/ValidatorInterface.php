<?php

namespace TYPO3\CMS\Extbase\Validation\Validator;

if (interface_exists('TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface')) {
    return;
}

interface ValidatorInterface
{
    /**
     * Checks if the given value is valid according to the validator, and returns
     * the Error Messages object which occurred.
     *
     * @param mixed $value The value that should be validated
     */
    public function validate($value);

    /**
     * Receive validator options from framework.
     */
    public function setOptions(array $options): void;

    /**
     * Returns the options of this validator which can be specified by setOptions().
     */
    public function getOptions(): array;
}
