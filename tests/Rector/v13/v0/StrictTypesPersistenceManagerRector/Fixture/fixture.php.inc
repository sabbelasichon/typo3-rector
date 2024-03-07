<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\StrictTypesPersistenceManagerRector\Fixture;

use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class MyPersistenceManager extends PersistenceManager
{
    protected $newObjects = [];
    protected $changedObjects;
    protected $addedObjects;
    protected $removedObjects;
    protected $queryFactory;
    protected $backend;
    protected $persistenceSession;
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\StrictTypesPersistenceManagerRector\Fixture;

use TYPO3\CMS\Extbase\Persistence\Generic\ObjectStorage;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryFactoryInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\BackendInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Session;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class MyPersistenceManager extends PersistenceManager
{
    protected array $newObjects = [];
    protected ObjectStorage $changedObjects;
    protected ObjectStorage $addedObjects;
    protected ObjectStorage $removedObjects;
    protected QueryFactoryInterface $queryFactory;
    protected BackendInterface $backend;
    protected Session $persistenceSession;
}

?>
