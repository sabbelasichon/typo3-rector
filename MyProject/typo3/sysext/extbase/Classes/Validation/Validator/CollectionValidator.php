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
use TYPO3\CMS\Extbase\Persistence\Generic\LazyObjectStorage;
use TYPO3\CMS\Extbase\Utility\TypeHandlingUtility;
use TYPO3\CMS\Extbase\Validation\ValidatorResolver;

/**
 * A generic collection validator.
 */
final class CollectionValidator extends AbstractGenericObjectValidator
{
    /**
     * @var array
     */
    protected $supportedOptions = [
        'elementValidator' => [null, 'The validator type to use for the collection elements', 'string'],
        'elementType' => [null, 'The type of the elements in the collection', 'string'],
    ];

    public function __construct(protected readonly ValidatorResolver $validatorResolver) {}

    /**
     * Checks if the given value is valid according to the validator, and returns
     * the Error Messages object which occurred.
     */
    public function validate(mixed $value): Result
    {
        $this->result = new Result();

        if ($this->acceptsEmptyValues === false || $this->isEmpty($value) === false) {
            if ((is_object($value) && !TypeHandlingUtility::isCollectionType(get_class($value))) && !is_array($value)) {
                $this->addError('The given subject was not a collection.', 1317204797);
                return $this->result;
            }
            if ($value instanceof LazyObjectStorage && !$value->isInitialized()) {
                return $this->result;
            }
            if (is_object($value)) {
                if ($this->isValidatedAlready($value)) {
                    return $this->result;
                }
                $this->markInstanceAsValidated($value);
            }
            $this->isValid($value);
        }
        return $this->result;
    }

    /**
     * Checks for a collection and if needed validates the items in the collection.
     * This is done with the specified element validator or a validator based on
     * the given element type.
     *
     * Either elementValidator or elementType must be given, otherwise validation
     * will be skipped.
     */
    protected function isValid(mixed $value): void
    {
        foreach ($value as $index => $collectionElement) {
            if (isset($this->options['elementValidator'])) {
                $collectionElementValidator = $this->validatorResolver->createValidator($this->options['elementValidator']);
            } elseif (isset($this->options['elementType'])) {
                $collectionElementValidator = $this->validatorResolver->getBaseValidatorConjunction($this->options['elementType']);
            } else {
                return;
            }
            if ($collectionElementValidator instanceof ObjectValidatorInterface) {
                $collectionElementValidator->setValidatedInstancesContainer($this->validatedInstancesContainer);
            }
            $this->result->forProperty((string)$index)->merge($collectionElementValidator->validate($collectionElement));
        }
    }
}
