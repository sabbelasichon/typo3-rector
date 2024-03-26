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

namespace TYPO3\CMS\Extbase\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Validate
{
    /**
     * @var string
     * @Required
     */
    public $validator = '';

    /**
     * @var string
     */
    public $param = '';

    /**
     * @var array<string, mixed>
     */
    public $options = [];

    /**
     * @param array{value?: mixed, validator?: string, options?: array<string, mixed>, param?: string} $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->validator = $values['value'];
        }

        if (isset($values['validator'])) {
            $this->validator = $values['validator'];
        }

        if (isset($values['options'])) {
            $this->options = $values['options'];
        }

        if (isset($values['param'])) {
            $this->param = $values['param'];
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $strings = [];

        if ($this->param !== '') {
            $strings[] = $this->param;
        }

        $strings[] = $this->validator;

        if (count($this->options) > 0) {
            $validatorOptionsStrings = [];
            foreach ($this->options as $optionKey => $optionValue) {
                $validatorOptionsStrings[] = $optionKey . '=' . $optionValue;
            }

            $strings[] = '(' . implode(', ', $validatorOptionsStrings) . ')';
        }

        return trim(implode(' ', $strings));
    }
}
