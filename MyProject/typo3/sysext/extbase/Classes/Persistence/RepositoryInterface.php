<?php

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

namespace TYPO3\CMS\Extbase\Persistence;

use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;

/**
 * Contract for a repository
 * @template T of object
 */
interface RepositoryInterface
{
    /**
     * Adds an object to this repository.
     *
     * @param object $object The object to add
     * @phpstan-param T $object
     */
    public function add($object);

    /**
     * Removes an object from this repository.
     *
     * @param object $object The object to remove
     * @phpstan-param T $object
     */
    public function remove($object);

    /**
     * Replaces an existing object with the same identifier by the given object
     *
     * @param object $modifiedObject The modified object
     * @phpstan-param T $modifiedObject
     */
    public function update($modifiedObject);

    /**
     * Returns all objects of this repository.
     *
     * @return iterable The iterable query result
     * @phpstan-return iterable<T>
     */
    public function findAll();

    /**
     * Returns the total number objects of this repository.
     *
     * @return int The object count
     */
    public function countAll();

    /**
     * Removes all objects of this repository as if remove() was called for
     * all of them.
     */
    public function removeAll();

    /**
     * Finds an object matching the given identifier.
     *
     * @param int $uid The identifier of the object to find
     * @return object The matching object if found, otherwise NULL
     * @phpstan-return T|null
     */
    public function findByUid($uid);

    /**
     * Finds an object matching the given identifier.
     *
     * @param mixed $identifier The identifier of the object to find
     * @return object The matching object if found, otherwise NULL
     * @phpstan-return T|null
     */
    public function findByIdentifier($identifier);

    /**
     * Sets the property names to order the result by per default.
     * Expected like this:
     * array(
     * 'foo' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
     * 'bar' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
     * )
     *
     * @param array $defaultOrderings The property names to order by
     */
    public function setDefaultOrderings(array $defaultOrderings);

    /**
     * Sets the default query settings to be used in this repository
     *
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface $defaultQuerySettings The query settings to be used by default
     */
    public function setDefaultQuerySettings(QuerySettingsInterface $defaultQuerySettings);

    /**
     * Returns a query for objects of this repository
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
     * @phpstan-return QueryInterface<T>
     */
    public function createQuery();
}
