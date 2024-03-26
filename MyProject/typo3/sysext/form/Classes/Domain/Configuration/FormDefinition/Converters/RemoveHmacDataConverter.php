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

namespace TYPO3\CMS\Form\Domain\Configuration\FormDefinition\Converters;

use TYPO3\CMS\Core\Utility\ArrayUtility;

/**
 * @internal
 */
class RemoveHmacDataConverter extends AbstractConverter
{
    /**
     * Remove the hmac data ("_orig_<propertyName>") for the corresponding property.
     *
     * @param mixed $value
     */
    public function __invoke(string $key, $value): void
    {
        $formDefinition = $this->converterDto->getFormDefinition();

        $propertyPathParts = explode('.', $key);
        array_pop($propertyPathParts);
        $propertyPath = implode('.', $propertyPathParts);
        $formDefinition = ArrayUtility::removeByPath($formDefinition, $propertyPath, '.');

        $this->converterDto->setFormDefinition($formDefinition);
    }
}
