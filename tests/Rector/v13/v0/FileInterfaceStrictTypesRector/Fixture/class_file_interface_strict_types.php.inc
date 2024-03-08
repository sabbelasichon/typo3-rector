<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\ResourceInterfaceStrictTypesRector\Fixture;

use TYPO3\CMS\Core\Resource\FileInterface;

class MyFile implements FileInterface
{

    public function hasProperty($key)
    {
    }

    public function getProperty($key)
    {
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\ResourceInterfaceStrictTypesRector\Fixture;

use TYPO3\CMS\Core\Resource\FileInterface;

class MyFile implements FileInterface
{

    public function hasProperty(string $key): bool
    {
    }

    public function getProperty(string $key): mixed
    {
    }
}

?>
