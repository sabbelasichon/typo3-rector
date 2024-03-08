<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\UseStrictTypesInExtbaseAbstractDomainObjectRector\Fixture;

use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;

class MyModel extends AbstractDomainObject
{
    /**
     * @var int<1, max>|null The uid of the record.
     */
    protected $uid;
    /**
     * @var int<1, max>|null The pid of the record.
     */
    protected $pid;
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\UseStrictTypesInExtbaseAbstractDomainObjectRector\Fixture;

use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;

class MyModel extends AbstractDomainObject
{
    /**
     * @var int<1, max>|null The uid of the record.
     */
    protected ?int $uid = null;
    /**
     * @var int<1, max>|null The pid of the record.
     */
    protected ?int $pid = null;
}

?>
