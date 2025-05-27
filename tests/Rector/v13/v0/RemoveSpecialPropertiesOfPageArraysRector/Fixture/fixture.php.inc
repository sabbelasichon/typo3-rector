<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveSpecialPropertiesOfPageArraysRector\Fixture;

$foo = $rows['_PAGES_OVERLAY_UID'] ?? null;
$bar = $rows['_PAGES_OVERLAY_REQUESTEDLANGUAGE'] ?? null;
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveSpecialPropertiesOfPageArraysRector\Fixture;

$foo = $rows['_LOCALIZED_UID'] ?? null;
$bar = $rows['_REQUESTED_OVERLAY_LANGUAGE'] ?? null;
?>
