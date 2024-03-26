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

namespace TYPO3\CMS\Core\Context;

use TYPO3\CMS\Core\Context\Exception\AspectPropertyNotFoundException;

/**
 * The Aspect is usually available as "date.*" properties in the Context.
 *
 * Contains the current time + date + timezone,
 * and needs a DateTimeImmutable object
 *
 * Allowed properties:
 * - timestamp - unix timestamp number
 * - timezone - America/Los_Angeles
 * - iso - datetime as string in ISO 8601 format, e.g. `2004-02-12T15:19:21+00:00`
 * - full - the DateTimeImmutable object
 */
class DateTimeAspect implements AspectInterface
{
    /**
     * @var \DateTimeImmutable
     */
    protected $dateTimeObject;

    public function __construct(\DateTimeImmutable $dateTimeImmutable)
    {
        $this->dateTimeObject = $dateTimeImmutable;
    }

    /**
     * Fetch a property of the date time object or the object itself ("full").
     *
     * @return \DateTimeImmutable|string|int
     * @throws AspectPropertyNotFoundException
     */
    public function get(string $name)
    {
        switch ($name) {
            case 'timestamp':
                return $this->dateTimeObject->getTimestamp();
            case 'iso':
                return $this->dateTimeObject->format('c');
            case 'timezone':
                return $this->dateTimeObject->format('e');
            case 'full':
                return $this->dateTimeObject;
            case 'accessTime':
                return $this->dateTimeObject->getTimestamp() - ($this->dateTimeObject->getTimestamp() % 60);
        }
        throw new AspectPropertyNotFoundException('Property "' . $name . '" not found in Aspect "' . __CLASS__ . '".', 1527778767);
    }

    /**
     * Return the full date time object
     */
    public function getDateTime(): \DateTimeImmutable
    {
        return $this->dateTimeObject;
    }
}
