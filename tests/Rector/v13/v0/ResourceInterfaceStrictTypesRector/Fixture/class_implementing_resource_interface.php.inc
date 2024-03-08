<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\ResourceInterfaceStrictTypesRector\Fixture;

use TYPO3\CMS\Core\Resource\ResourceInterface;

class MyResource implements ResourceInterface
{

    public function getIdentifier()
    {
    }

    public function getName()
    {
    }

    public function getStorage()
    {
    }

    public function getHashedIdentifier()
    {
    }

    public function getParentFolder()
    {
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\ResourceInterfaceStrictTypesRector\Fixture;

use TYPO3\CMS\Core\Resource\ResourceStorage;
use TYPO3\CMS\Core\Resource\FolderInterface;
use TYPO3\CMS\Core\Resource\ResourceInterface;

class MyResource implements ResourceInterface
{

    public function getIdentifier(): string
    {
    }

    public function getName(): string
    {
    }

    public function getStorage(): ResourceStorage
    {
    }

    public function getHashedIdentifier(): string
    {
    }

    public function getParentFolder(): FolderInterface
    {
    }
}

?>
