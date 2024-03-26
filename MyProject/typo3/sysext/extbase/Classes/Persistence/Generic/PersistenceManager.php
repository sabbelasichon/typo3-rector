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

namespace TYPO3\CMS\Extbase\Persistence\Generic;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * The Extbase Persistence Manager
 */
class PersistenceManager implements PersistenceManagerInterface, SingletonInterface
{
    /**
     * @var array
     */
    protected $newObjects = [];

    /**
     * @var ObjectStorage
     */
    protected $changedObjects;

    /**
     * @var ObjectStorage
     */
    protected $addedObjects;

    /**
     * @var ObjectStorage
     */
    protected $removedObjects;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\QueryFactoryInterface
     */
    protected $queryFactory;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\BackendInterface
     */
    protected $backend;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\Session
     */
    protected $persistenceSession;

    /**
     * Create new instance
     * @internal only to be used within Extbase, not part of TYPO3 Core API.
     */
    public function __construct(
        QueryFactoryInterface $queryFactory,
        BackendInterface $backend,
        Session $persistenceSession
    ) {
        $this->queryFactory = $queryFactory;
        $this->backend = $backend;
        $this->persistenceSession = $persistenceSession;

        $this->addedObjects = new ObjectStorage();
        $this->removedObjects = new ObjectStorage();
        $this->changedObjects = new ObjectStorage();
    }

    /**
     * Registers a repository
     *
     * @param string $className The class name of the repository to be registered
     * @internal only to be used within Extbase, not part of TYPO3 Core API.
     */
    public function registerRepositoryClassName($className) {}

    /**
     * Returns the number of records matching the query.
     *
     * @return int
     */
    public function getObjectCountByQuery(QueryInterface $query)
    {
        return $this->backend->getObjectCountByQuery($query);
    }

    /**
     * Returns the object data matching the $query.
     *
     * @return array
     */
    public function getObjectDataByQuery(QueryInterface $query)
    {
        return $this->backend->getObjectDataByQuery($query);
    }

    /**
     * Returns the (internal) identifier for the object, if it is known to the
     * backend. Otherwise NULL is returned.
     *
     * Note: this returns an identifier even if the object has not been
     * persisted in case of AOP-managed entities. Use isNewObject() if you need
     * to distinguish those cases.
     *
     * @param object $object
     * @return mixed The identifier for the object if it is known, or NULL
     */
    public function getIdentifierByObject($object)
    {
        return $this->backend->getIdentifierByObject($object);
    }

    /**
     * Returns the object with the (internal) identifier, if it is known to the
     * backend. Otherwise NULL is returned.
     *
     * @param mixed $identifier
     * @param string $objectType
     * @param bool $useLazyLoading Set to TRUE if you want to use lazy loading for this object
     * @return object The object for the identifier if it is known, or NULL
     */
    public function getObjectByIdentifier($identifier, $objectType = null, $useLazyLoading = false)
    {
        // @todo: change argument $objectType, must be a string, not nullable
        $objectType ??= '';

        if (isset($this->newObjects[$identifier])) {
            return $this->newObjects[$identifier];
        }
        if ($this->persistenceSession->hasIdentifier((string)$identifier, $objectType)) {
            return $this->persistenceSession->getObjectByIdentifier((string)$identifier, $objectType);
        }
        return $this->backend->getObjectByIdentifier((string)$identifier, $objectType);
    }

    /**
     * Commits new objects and changes to objects in the current persistence
     * session into the backend.
     */
    public function persistAll()
    {
        // hand in only aggregate roots, leaving handling of subobjects to
        // the underlying storage layer
        // reconstituted entities must be fetched from the session and checked
        // for changes by the underlying backend as well!
        $this->backend->setAggregateRootObjects($this->addedObjects);
        $this->backend->setChangedEntities($this->changedObjects);
        $this->backend->setDeletedEntities($this->removedObjects);
        $this->backend->commit();

        $this->addedObjects = new ObjectStorage();
        $this->removedObjects = new ObjectStorage();
        $this->changedObjects = new ObjectStorage();
    }

    /**
     * Return a query object for the given type.
     *
     * @param string $type
     * @return QueryInterface
     * @internal only to be used within Extbase, not part of TYPO3 Core API.
     * @template T of object
     * @phpstan-param class-string<T> $type
     * @phpstan-return QueryInterface<T>
     */
    public function createQueryForType($type)
    {
        return $this->queryFactory->create($type);
    }

    /**
     * Adds an object to the persistence.
     *
     * @param object $object The object to add
     */
    public function add($object)
    {
        $this->addedObjects->attach($object);
        $this->removedObjects->detach($object);
    }

    /**
     * Removes an object to the persistence.
     *
     * @param object $object The object to remove
     */
    public function remove($object)
    {
        if ($this->addedObjects->contains($object)) {
            $this->addedObjects->detach($object);
        } else {
            $this->removedObjects->attach($object);
        }
    }

    /**
     * Update an object in the persistence.
     *
     * @param object $object The modified object
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function update($object)
    {
        if ($this->isNewObject($object)) {
            throw new UnknownObjectException('The object of type "' . get_class($object) . '" given to update must be persisted already, but is new.', 1249479819);
        }
        $this->changedObjects->attach($object);
    }

    /**
     * Initializes the persistence manager, called by Extbase.
     * @internal only to be used within Extbase, not part of TYPO3 Core API.
     */
    public function initializeObject()
    {
        $this->backend->setPersistenceManager($this);
    }

    /**
     * Clears the in-memory state of the persistence.
     *
     * Managed instances become detached, any fetches will
     * return data directly from the persistence "backend".
     *
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\NotImplementedException
     * @internal only to be used within Extbase, not part of TYPO3 Core API.
     */
    public function clearState()
    {
        $this->newObjects = [];
        $this->addedObjects = new ObjectStorage();
        $this->removedObjects = new ObjectStorage();
        $this->changedObjects = new ObjectStorage();
        $this->persistenceSession->destroy();
    }

    /**
     * Checks if the given object has ever been persisted.
     *
     * @param object $object The object to check
     * @return bool TRUE if the object is new, FALSE if the object exists in the persistence session
     */
    public function isNewObject($object)
    {
        return $this->persistenceSession->hasObject($object) === false;
    }

    /**
     * Registers an object which has been created or cloned during this request.
     *
     * A "new" object does not necessarily
     * have to be known by any repository or be persisted in the end.
     *
     * Objects registered with this method must be known to the getObjectByIdentifier()
     * method.
     *
     * @param object $object The new object to register
     * @internal only to be used within Extbase, not part of TYPO3 Core API.
     */
    public function registerNewObject($object)
    {
        $identifier = $this->getIdentifierByObject($object);
        $this->newObjects[$identifier] = $object;
    }

    /**
     * Tear down the persistence
     *
     * This method is called in functional tests to reset the storage between tests.
     * The implementation is optional and depends on the underlying persistence backend.
     * @internal only to be used within Extbase, not part of TYPO3 Core API.
     */
    public function tearDown(): void
    {
        if (method_exists($this->backend, 'tearDown')) {
            $this->backend->tearDown();
        }
    }
}
