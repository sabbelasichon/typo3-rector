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

/*
 * Inspired by and partially taken from the Neos.Form package (www.neos.io)
 */

namespace TYPO3\CMS\Form\Domain\Runtime;

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\Exception\MissingArrayPathException;

/**
 * The current state of the form which is attached to the {@link FormRuntime}
 * and saved in a session or the client.
 *
 * Scope: frontend
 * **This class is NOT meant to be sub classed by developers.**
 * @internal
 */
class FormState
{
    /**
     * Constant which means that we are currently not on any page; i.e. the form
     * has never rendered before.
     */
    public const NOPAGE = -1;

    /**
     * The last displayed page index
     *
     * @var int
     */
    protected $lastDisplayedPageIndex = self::NOPAGE;

    /**
     * @var array
     */
    protected $formValues = [];

    /**
     * @return bool FALSE if the form has never been submitted before, TRUE otherwise
     */
    public function isFormSubmitted(): bool
    {
        return $this->lastDisplayedPageIndex !== self::NOPAGE;
    }

    public function getLastDisplayedPageIndex(): int
    {
        return $this->lastDisplayedPageIndex;
    }

    public function setLastDisplayedPageIndex(int $lastDisplayedPageIndex)
    {
        $this->lastDisplayedPageIndex = $lastDisplayedPageIndex;
    }

    public function getFormValues(): array
    {
        return $this->formValues;
    }

    /**
     * @param mixed $value
     */
    public function setFormValue(string $propertyPath, $value)
    {
        $this->formValues = ArrayUtility::setValueByPath(
            $this->formValues,
            $propertyPath,
            $value,
            '.'
        );
    }

    /**
     * @return mixed
     */
    public function getFormValue(string $propertyPath)
    {
        try {
            return ArrayUtility::getValueByPath($this->formValues, $propertyPath, '.');
        } catch (MissingArrayPathException $exception) {
            return null;
        }
    }
}
