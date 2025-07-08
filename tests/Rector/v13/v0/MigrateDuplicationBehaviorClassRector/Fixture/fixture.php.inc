<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateDuplicationBehaviorClassRector\Fixture;

use TYPO3\CMS\Core\Resource\DuplicationBehavior as DeprecatedDuplicationBehavior;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;

$file = new File();
$file->copyTo(new Folder(), null, DeprecatedDuplicationBehavior::REPLACE);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateDuplicationBehaviorClassRector\Fixture;

use TYPO3\CMS\Core\Resource\DuplicationBehavior as DeprecatedDuplicationBehavior;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;

$file = new File();
$file->copyTo(new Folder(), null, \TYPO3\CMS\Core\Resource\Enum\DuplicationBehavior::REPLACE);

?>
