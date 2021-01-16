<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Name\RenameClassRector\SkipAutoImportNames;

$class = new \Ssch\TYPO3Rector\Tests\Rector\Name\RenameClassRector\Source\FirstOriginalClass();

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Name\RenameClassRector\SkipAutoImportNames;

$class = new \Ssch\TYPO3Rector\Tests\Rector\Name\RenameClassRector\Source\SecondOriginalClass();

?>
