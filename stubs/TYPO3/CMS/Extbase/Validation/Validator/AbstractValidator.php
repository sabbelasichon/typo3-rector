<?php

namespace TYPO3\CMS\Extbase\Validation\Validator;

if (class_exists('TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator')) {
    return;
}

abstract class AbstractValidator implements ValidatorInterface
{
    public function validate($value)
    {

    }

    public function getOptions(): array
    {
        return [];
    }

    public function setOptions(array $options): void
    {

    }
}
