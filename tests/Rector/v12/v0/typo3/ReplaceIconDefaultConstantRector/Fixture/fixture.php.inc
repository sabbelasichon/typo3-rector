<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplaceIconDefaultConstantRector\Fixture;

use TYPO3\CMS\Core\Imaging\Icon;

$iconDefaultSize = Icon::SIZE_DEFAULT;

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplaceIconDefaultConstantRector\Fixture;

use TYPO3\CMS\Core\Imaging\Icon;

$iconDefaultSize = Icon::SIZE_MEDIUM;

?>
