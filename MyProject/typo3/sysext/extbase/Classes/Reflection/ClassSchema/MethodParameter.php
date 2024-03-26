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

namespace TYPO3\CMS\Extbase\Reflection\ClassSchema;

/**
 * Class TYPO3\CMS\Extbase\Reflection\ClassSchema\Property
 * @internal only to be used within Extbase, not part of TYPO3 Core API.
 */
class MethodParameter
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $definition;

    public function __construct(string $name, array $definition)
    {
        $this->name = $name;

        $defaults = [
            'type' => null,
            'array' => false,
            'optional' => false,
            'hasDefaultValue' => false,
            'defaultValue' => null,
            'dependency' => null,
            'ignoreValidation' => false,
            'validators' => [],
            'position' => -1,
        ];

        foreach ($defaults as $key => $defaultValue) {
            if (!isset($definition[$key])) {
                $definition[$key] = $defaultValue;
            }
        }

        $this->definition = $definition;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): ?string
    {
        return $this->definition['type'];
    }

    public function isArray(): bool
    {
        return $this->definition['array'];
    }

    public function hasDefaultValue(): bool
    {
        return $this->definition['hasDefaultValue'];
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->definition['defaultValue'];
    }

    public function getValidators(): array
    {
        return $this->definition['validators'];
    }

    public function ignoreValidation(): bool
    {
        return $this->definition['ignoreValidation'];
    }

    public function isOptional(): bool
    {
        return $this->definition['optional'];
    }

    public function getDependency(): ?string
    {
        return $this->definition['dependency'];
    }

    public function getPosition(): int
    {
        return $this->definition['position'];
    }
}
