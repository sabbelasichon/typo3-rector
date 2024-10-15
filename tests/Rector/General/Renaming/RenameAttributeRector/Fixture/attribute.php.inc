<?php

namespace Ssch\TYPO3Rector\Tests\Rector\General\Renaming\RenameAttributeRector\Fixture;

use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Attribute\Controller;

#[Controller]
#[AsController]
class MyClass
{

}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\General\Renaming\RenameAttributeRector\Fixture;

use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Attribute\Controller;

#[AsController]
#[AsController]
class MyClass
{

}

?>
