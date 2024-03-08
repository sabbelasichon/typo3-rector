<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\ResourceInterfaceStrictTypesRector\Fixture;

use TYPO3\CMS\Core\Resource\AbstractFile;

class MyFile extends AbstractFile
{
    public function getIdentifier()
    {
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\ResourceInterfaceStrictTypesRector\Fixture;

use TYPO3\CMS\Core\Resource\AbstractFile;

class MyFile extends AbstractFile
{
    public function getIdentifier(): string
    {
    }
}

?>
