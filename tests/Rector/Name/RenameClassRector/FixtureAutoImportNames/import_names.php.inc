<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Name\RenameClassRector\FixtureAutoImportNames;

$class = new \Ssch\TYPO3Rector\Tests\Rector\Name\RenameClassRector\Source\FirstOriginalClass();

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Name\RenameClassRector\FixtureAutoImportNames;

use Ssch\TYPO3Rector\Tests\Rector\Name\RenameClassRector\Source\FirstOriginalClass;
$class = new FirstOriginalClass();

?>
