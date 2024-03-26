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

namespace TYPO3\CMS\Reactions;

use TYPO3\CMS\Reactions\Reaction\ReactionInterface;

/**
 * Registry contains all possible reaction types which are available to the system
 *
 * @internal
 */
class ReactionRegistry
{
    /**
     * @param \IteratorAggregate<ReactionInterface> $registeredReactions
     */
    public function __construct(
        private readonly \IteratorAggregate $registeredReactions
    ) {}

    /**
     * @return \IteratorAggregate<ReactionInterface>
     */
    public function getAvailableReactionTypes(): \IteratorAggregate
    {
        return $this->registeredReactions;
    }

    public function getReactionByType(string $type): ?ReactionInterface
    {
        return iterator_to_array($this->registeredReactions->getIterator())[$type] ?? null;
    }
}
